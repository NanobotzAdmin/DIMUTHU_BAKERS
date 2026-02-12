@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('distributorCustomerManagement.index') }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $customer['businessName'] }}</h1>
                            @if($customer['tradeName'])
                                <span class="text-gray-500">({{ $customer['tradeName'] }})</span>
                            @endif
                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $customer['customerType'] === 'b2b' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $customer['customerType'] }}
                            </span>
                            @if($customer['b2bType'])
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 capitalize">
                                    {{ str_replace('_', ' ', $customer['b2bType']) }}
                                </span>
                            @endif
                            @if($customer['isVerified'])
                                <span class="px-2 py-0.5 rounded bg-green-100 text-green-800 text-xs flex items-center gap-1">
                                    <i class="bi bi-patch-check-fill"></i> Verified
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm mt-1">{{ $customer['customerCode'] }}</p>
                    </div>
                </div>
                <button onclick="openCustomerModal(@json($customer))" class="px-4 py-2 bg-[#D4A017] hover:bg-[#B8860B] text-white rounded-lg flex items-center shadow-sm">
                    <i class="bi bi-pencil mr-2"></i> Edit Customer
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Contact Info -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-person text-gray-400"></i> Contact Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Contact Person</div>
                            <div class="text-gray-900">{{ $customer['contact']['contactPerson'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Phone Number</div>
                            <div class="text-gray-900 flex items-center gap-2">
                                <i class="bi bi-telephone text-gray-400"></i> {{ $customer['contact']['phoneNumber'] }}
                            </div>
                        </div>
                        @if(isset($customer['contact']['alternatePhone']))
                            <div>
                                <div class="text-xs text-gray-500 font-medium uppercase mb-1">Alternate Phone</div>
                                <div class="text-gray-900">{{ $customer['contact']['alternatePhone'] }}</div>
                            </div>
                        @endif
                        @if(isset($customer['contact']['email']))
                            <div>
                                <div class="text-xs text-gray-500 font-medium uppercase mb-1">Email</div>
                                <div class="text-gray-900 flex items-center gap-2">
                                    <i class="bi bi-envelope text-gray-400"></i> {{ $customer['contact']['email'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Location -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-geo-alt text-gray-400"></i> Location
                    </h2>
                    <div class="flex items-start gap-3 mb-4">
                        <i class="bi bi-geo-alt-fill text-red-600 text-xl"></i>
                        <div>
                            <div class="text-gray-900 font-medium">{{ $customer['location']['address'] }}</div>
                            <div class="text-gray-600 text-sm">{{ $customer['location']['city'] }}, {{ $customer['location']['district'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">Lat: {{ $customer['location']['latitude'] }}, Lng: {{ $customer['location']['longitude'] }}</div>
                        </div>
                    </div>
                    <!-- Placeholder Map -->
                    <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center relative overflow-hidden border border-gray-300">
                        <div class="absolute inset-0 bg-blue-50 opacity-50" 
                            style="background-image: repeating-linear-gradient(45deg, #e5e7eb 25%, transparent 25%, transparent 75%, #e5e7eb 75%, #e5e7eb), repeating-linear-gradient(45deg, #e5e7eb 25%, #f3f4f6 25%, #f3f4f6 75%, #e5e7eb 75%, #e5e7eb); background-position: 0 0, 10px 10px; background-size: 20px 20px;">
                        </div>
                        <div class="z-10 text-center">
                            <i class="bi bi-map text-gray-400 text-4xl mb-2 block"></i>
                            <span class="text-gray-500 font-medium">Map View Integration</span>
                        </div>
                    </div>
                </div>

                <!-- Assignment -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                     <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-signpost-split text-gray-400"></i> Assignment & Schedule
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Assigned Agent</div>
                            <div class="text-gray-900 font-medium">{{ $agent['agentName'] }}</div>
                            <div class="text-xs text-gray-500">{{ $agent['agentCode'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Assigned Route</div>
                            <div class="text-gray-900 font-medium">{{ $route['routeName'] }}</div>
                            <div class="text-xs text-gray-500">{{ $route['routeCode'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Stop Sequence</div>
                            <div class="text-gray-900">Stop #{{ $customer['stopSequence'] }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Visit Frequency</div>
                            <div class="text-gray-900 capitalize">{{ $customer['visitSchedule']['frequency'] }}</div>
                        </div>
                        @if(count($customer['visitSchedule']['preferredDays']) > 0)
                            <div class="md:col-span-2">
                                 <div class="text-xs text-gray-500 font-medium uppercase mb-2">Preferred Visit Days</div>
                                 <div class="flex flex-wrap gap-2">
                                    @foreach($customer['visitSchedule']['preferredDays'] as $day)
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs capitalize font-medium border border-blue-100">{{ $day }}</span>
                                    @endforeach
                                 </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Instructions -->
                @if($customer['specialInstructions'] || $customer['deliveryInstructions'])
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                            <i class="bi bi-file-text text-gray-400"></i> Instructions & Notes
                        </h2>
                        <div class="space-y-4">
                            @if($customer['specialInstructions'])
                                <div>
                                    <div class="text-xs text-gray-500 font-medium uppercase mb-1">Special Instructions</div>
                                    <div class="bg-yellow-50 text-gray-800 p-3 rounded-lg text-sm border border-yellow-100">
                                        {{ $customer['specialInstructions'] }}
                                    </div>
                                </div>
                            @endif
                            @if($customer['deliveryInstructions'])
                                <div>
                                    <div class="text-xs text-gray-500 font-medium uppercase mb-1">Delivery Instructions</div>
                                    <div class="bg-blue-50 text-gray-800 p-3 rounded-lg text-sm border border-blue-100">
                                        {{ $customer['deliveryInstructions'] }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Stats -->
            <div class="space-y-6">
                <!-- Financial Summary -->
                @if(isset($customer['creditTerms']))
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                        <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                            <i class="bi bi-cash-coin text-gray-400"></i> Financial Summary
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <div class="text-xs text-gray-500 font-medium uppercase mb-1">Current Balance</div>
                                <div class="text-2xl font-bold {{ $customer['currentBalance'] > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                    Rs. {{ number_format($customer['currentBalance']) }}
                                </div>
                                @if($customer['currentBalance'] > 0)
                                    @php
                                        $overdue = $customer['creditDays'] > $customer['creditTerms']['paymentTermsDays'];
                                        $diff = $customer['creditDays'] - $customer['creditTerms']['paymentTermsDays'];
                                    @endphp
                                    <div class="text-sm mt-1 {{ $overdue ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                        {{ $customer['creditDays'] }} days outstanding
                                        @if($overdue) <span class="block text-xs">(Overdue by {{ $diff }} days)</span> @endif
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-500 font-medium uppercase">Credit Limit</span>
                                    <span class="text-sm font-bold text-gray-900">Rs. {{ number_format($customer['creditTerms']['creditLimit']) }}</span>
                                </div>
                                @php
                                    $percent = min(100, ($customer['currentBalance'] / $customer['creditTerms']['creditLimit']) * 100);
                                @endphp
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <div class="text-xs text-gray-400 mt-1 text-right">{{ number_format($percent, 1) }}% utilized</div>
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                <div class="text-xs text-gray-500 font-medium uppercase mb-2">Invoice Aging</div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Current (0-30)</span>
                                        <span class="text-gray-900 font-medium">Rs. {{ number_format($customer['invoiceAging']['current']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">31-60 days</span>
                                        <span class="text-orange-600 font-medium">Rs. {{ number_format($customer['invoiceAging']['days30']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">61-90 days</span>
                                        <span class="text-orange-600 font-medium">Rs. {{ number_format($customer['invoiceAging']['days60']) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">90+ days</span>
                                        <span class="text-red-600 font-bold">Rs. {{ number_format($customer['invoiceAging']['days90Plus']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Sales Stats -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-graph-up text-gray-400"></i> Sales Statistics
                    </h2>
                    <div class="space-y-4">
                        <div>
                             <div class="text-xs text-gray-500 font-medium uppercase mb-1">Total Sales</div>
                             <div class="text-xl font-bold text-gray-900">Rs. {{ number_format($customer['totalSales']) }}</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="text-xs text-gray-500 font-medium uppercase mb-1">Total Orders</div>
                                <div class="text-lg font-medium text-gray-900">{{ $customer['totalOrders'] }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 font-medium uppercase mb-1">Avg Order</div>
                                <div class="text-lg font-medium text-gray-900">Rs. {{ number_format($customer['averageOrderValue']) }}</div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-4 text-sm">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Last Order</span>
                                <span class="text-gray-900">{{ date('M d, Y', strtotime($customer['lastOrderDate'])) }}</span>
                            </div>
                             <div class="flex justify-between">
                                <span class="text-gray-600">Last Payment</span>
                                <span class="text-gray-900">{{ date('M d, Y', strtotime($customer['lastPaymentDate'])) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit History Overview -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                     <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-calendar-check text-gray-400"></i> Visit History
                    </h2>
                    <div class="space-y-4">
                        <div>
                             <div class="text-xs text-gray-500 font-medium uppercase mb-1">Total Visits</div>
                             <div class="text-xl font-bold text-gray-900">{{ $customer['visitSchedule']['visitCount'] }}</div>
                        </div>

                        @if($customer['visitSchedule']['lastVisitDate'])
                        <div>
                            <div class="text-xs text-gray-500 font-medium uppercase mb-1">Last Visit</div>
                            <div class="text-gray-900">{{ date('M d, Y', strtotime($customer['visitSchedule']['lastVisitDate'])) }}</div>
                        </div>
                        @endif
                        
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                            <div class="flex items-center gap-2 text-blue-800 text-sm font-medium mb-1">
                                <i class="bi bi-clock-history"></i> Next Scheduled Visit
                            </div>
                            <div class="text-blue-900 font-bold">
                                {{ date('l, M d, Y', strtotime($customer['visitSchedule']['nextScheduledVisit'])) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h2 class="text-gray-900 font-bold mb-4 flex items-center gap-2">
                        <i class="bi bi-info-circle text-gray-400"></i> System Information
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Customer Code</span>
                            <span class="text-gray-900 font-medium">{{ $customer['customerCode'] }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status</span>
                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $customer['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $customer['status'] }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created</span>
                            <span class="text-gray-900">{{ date('M d, Y', strtotime($customer['createdAt'])) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="text-gray-900">{{ date('M d, Y', strtotime($customer['updatedAt'])) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-data="{ tab: 'orders' }">
            <div class="flex border-b border-gray-200 bg-gray-50">
                <button @click="tab = 'orders'" :class="{ 'border-b-2 border-[#D4A017] text-gray-900 bg-white': tab === 'orders', 'text-gray-500 hover:text-gray-700': tab !== 'orders' }" class="px-6 py-4 font-medium text-sm transition-colors flex items-center gap-2">
                    <i class="bi bi-box-seam"></i> Order History
                </button>
                <button @click="tab = 'payments'" :class="{ 'border-b-2 border-[#D4A017] text-gray-900 bg-white': tab === 'payments', 'text-gray-500 hover:text-gray-700': tab !== 'payments' }" class="px-6 py-4 font-medium text-sm transition-colors flex items-center gap-2">
                    <i class="bi bi-credit-card"></i> Payment History
                </button>
                <button @click="tab = 'visits'" :class="{ 'border-b-2 border-[#D4A017] text-gray-900 bg-white': tab === 'visits', 'text-gray-500 hover:text-gray-700': tab !== 'visits' }" class="px-6 py-4 font-medium text-sm transition-colors flex items-center gap-2">
                    <i class="bi bi-geo"></i> Visit Log
                </button>
            </div>

            <div class="p-6">
                <!-- Orders Tab -->
                <!-- Orders Tab -->
                <div x-show="tab === 'orders'">
                    <!-- Summary Stats -->
                     <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Total Orders</div>
                                <i class="bi bi-box-seam text-blue-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ count($orders) }}</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Total Value</div>
                                <i class="bi bi-currency-dollar text-green-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">Rs. {{ number_format(collect($orders)->sum('totalAmount')) }}</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Avg Order Value</div>
                                <i class="bi bi-currency-dollar text-orange-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">Rs. {{ count($orders) > 0 ? number_format(collect($orders)->avg('totalAmount')) : 0 }}</div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2 relative">
                                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" placeholder="Search orders..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                                    <option value="all">All Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <input type="date" placeholder="From date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>

                            <!-- Date To -->
                            <div>
                                <input type="date" placeholder="To date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                        </div>

                         <!-- Export Button -->
                        <div class="flex justify-end mt-4">
                            <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2">
                                <i class="bi bi-download"></i> Export to CSV
                            </button>
                        </div>
                    </div>

                    <!-- Orders List -->
                    <div class="space-y-4">
                        @forelse($orders as $order)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row items-start justify-between gap-4">
                                    <div class="flex-1 w-full">
                                        <div class="flex items-center gap-3 mb-3 flex-wrap">
                                            <h3 class="text-gray-900 font-bold">{{ $order['orderNumber'] }}</h3>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'confirmed' => 'bg-blue-100 text-blue-800',
                                                    'delivered' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-red-100 text-red-800'
                                                ];
                                                $statusIcon = [
                                                    'pending' => 'bi-clock',
                                                    'confirmed' => 'bi-check-circle',
                                                    'delivered' => 'bi-check-circle-fill',
                                                    'cancelled' => 'bi-x-circle'
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase flex items-center gap-1 {{ $statusColors[$order['status']] }}">
                                                <i class="bi {{ $statusIcon[$order['status']] }}"></i> {{ ucfirst($order['status']) }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded bg-gray-100 text-gray-800 text-xs font-medium capitalize">
                                                {{ $order['paymentMethod'] }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3 text-sm">
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="bi bi-calendar"></i>
                                                <span>Order: {{ $order['date'] }}</span>
                                            </div>
                                            @if(isset($order['deliveryDate']))
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="bi bi-truck"></i>
                                                <span>Delivered: {{ $order['deliveryDate'] }}</span>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="text-sm text-gray-600 mb-3">
                                            Agent: <span class="text-gray-900 font-medium">{{ $order['agentName'] ?? 'Unknown' }}</span>
                                        </div>

                                        <!-- Items Summary -->
                                        <div class="bg-gray-50 rounded-lg p-3 w-full">
                                            <div class="text-xs text-gray-500 font-medium uppercase mb-2">Order Items ({{ count($order['items']) }})</div>
                                            <div class="space-y-1">
                                                @foreach(array_slice($order['items'], 0, 3) as $item)
                                                    <div class="flex justify-between text-sm">
                                                        <span class="text-gray-900">
                                                            {{ $item['productName'] }} × {{ $item['quantity'] }}
                                                        </span>
                                                        <span class="text-gray-600">Rs. {{ number_format($item['total']) }}</span>
                                                    </div>
                                                @endforeach
                                                @if(count($order['items']) > 3)
                                                    <div class="text-xs text-gray-500 italic">
                                                        +{{ count($order['items']) - 3 }} more items
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if($order['notes'])
                                            <div class="mt-3 text-sm text-gray-600">
                                                <span class="text-gray-500 font-medium">Notes:</span> <i>{{ $order['notes'] }}</i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Right: Amount & Actions -->
                                    <div class="flex flex-col items-end gap-3 min-w-[150px]">
                                        <div class="text-right">
                                            <div class="text-sm text-gray-600">Total Amount</div>
                                            <div class="text-xl font-bold text-gray-900">Rs. {{ number_format($order['totalAmount']) }}</div>
                                        </div>

                                        <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2 transition-colors">
                                            <i class="bi bi-eye"></i> View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                                <i class="bi bi-box-seam text-gray-400 text-4xl mb-3 block"></i>
                                <p class="text-gray-600 font-medium">No orders found</p>
                                <p class="text-sm text-gray-500 mt-1">Try adjusting your filters</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Payments Tab -->
                <div x-show="tab === 'payments'" style="display: none;">
                    <!-- Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Total Payments</div>
                                <i class="bi bi-check-circle text-green-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ count($payments) }}</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Total Received</div>
                                <i class="bi bi-currency-dollar text-green-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">Rs. {{ number_format(collect($payments)->sum('amount')) }}</div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Avg Payment</div>
                                <i class="bi bi-currency-dollar text-blue-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">Rs. {{ count($payments) > 0 ? number_format(collect($payments)->avg('amount')) : 0 }}</div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Search -->
                            <div class="md:col-span-2 relative">
                                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" placeholder="Search payments..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>

                            <!-- Method Filter -->
                            <div>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                                    <option value="all">All Methods</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <input type="date" placeholder="From date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>

                            <!-- Date To -->
                            <div>
                                <input type="date" placeholder="To date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                        </div>

                         <!-- Export Button -->
                        <div class="flex justify-end mt-4">
                            <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2">
                                <i class="bi bi-download"></i> Export to CSV
                            </button>
                        </div>
                    </div>

                    <!-- Payments List -->
                    <div class="space-y-4">
                        @forelse($payments as $payment)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <div class="flex items-start justify-between gap-4">
                                    <!-- Left: Payment Info -->
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-3 flex-wrap">
                                            <h3 class="text-gray-900 font-bold">{{ $payment['receiptNumber'] }}</h3>
                                            @php
                                                $methodColors = [
                                                    'cash' => 'bg-green-100 text-green-800',
                                                    'cheque' => 'bg-blue-100 text-blue-800',
                                                    'bank_transfer' => 'bg-purple-100 text-purple-800',
                                                    'card' => 'bg-orange-100 text-orange-800'
                                                ];
                                                $methodLabel = [
                                                    'cash' => 'Cash',
                                                    'cheque' => 'Cheque',
                                                    'bank_transfer' => 'Bank Transfer',
                                                    'card' => 'Card'
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $methodColors[$payment['method']] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $methodLabel[$payment['method']] ?? ucfirst(str_replace('_', ' ', $payment['method'])) }}
                                            </span>
                                            
                                            @if(isset($payment['allocations']) && count($payment['allocations']) > 0)
                                                <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-medium flex items-center gap-1">
                                                    <i class="bi bi-file-text"></i> {{ count($payment['allocations']) }} Invoice{{ count($payment['allocations']) > 1 ? 's' : '' }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3 text-sm">
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="bi bi-calendar"></i>
                                                <span>{{ date('d M Y', strtotime($payment['date'])) }}</span>
                                            </div>
                                            <div class="text-gray-600">
                                                Agent: <span class="text-gray-900 font-medium">{{ $payment['agentName'] ?? 'Unknown' }}</span>
                                            </div>
                                        </div>

                                        @if(isset($payment['reference']))
                                            <div class="text-sm text-gray-600 mb-3">
                                                Reference: <span class="text-gray-900 font-medium">{{ $payment['reference'] }}</span>
                                            </div>
                                        @endif

                                        <!-- FIFO Allocations -->
                                        @if(isset($payment['allocations']) && count($payment['allocations']) > 0)
                                            <div class="bg-blue-50 rounded-lg p-3 w-full border border-blue-100">
                                                <div class="text-xs text-blue-900 font-medium uppercase mb-2 flex items-center gap-2">
                                                    <i class="bi bi-file-text"></i> Invoice Allocations (FIFO)
                                                </div>
                                                <div class="space-y-1">
                                                    @foreach($payment['allocations'] as $alloc)
                                                        <div class="flex justify-between text-sm">
                                                            <span class="text-blue-900 font-medium">{{ $alloc['invoiceNumber'] }}</span>
                                                            <span class="text-blue-900">Rs. {{ number_format($alloc['allocatedAmount']) }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if(isset($payment['notes']) && $payment['notes'])
                                            <div class="mt-3 text-sm text-gray-600">
                                                <span class="text-gray-500 font-medium">Notes:</span> <i>{{ $payment['notes'] }}</i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Right: Amount -->
                                    <div class="text-right min-w-[120px]">
                                        <div class="text-sm text-gray-600 mb-1">Amount Received</div>
                                        <div class="text-2xl font-bold text-green-600">Rs. {{ number_format($payment['amount']) }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                                <i class="bi bi-currency-dollar text-gray-400 text-4xl mb-3 block"></i>
                                <p class="text-gray-600 font-medium">No payments found</p>
                                <p class="text-sm text-gray-500 mt-1">Try adjusting your filters</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Visits Tab -->
                <div x-show="tab === 'visits'" style="display: none;">
                    <!-- Summary Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Total Visits</div>
                                <i class="bi bi-geo-alt text-blue-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ count($visits) }}</div>
                        </div>
                         <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Completed</div>
                                <i class="bi bi-calendar-check text-green-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ collect($visits)->where('status', 'completed')->count() }}</div>
                             <div class="text-xs text-gray-500 mt-1">
                                {{ count($visits) > 0 ? round((collect($visits)->where('status', 'completed')->count() / count($visits)) * 100) : 0 }}% completion rate
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Total Duration</div>
                                <i class="bi bi-clock text-orange-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ collect($visits)->sum('duration') }} min</div>
                        </div>
                         <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-sm text-gray-600">Avg Duration</div>
                                <i class="bi bi-clock-history text-purple-600"></i>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ count($visits) > 0 ? round(collect($visits)->avg('duration')) : 0 }} min</div>
                        </div>
                    </div>

                    <!-- Filters -->
                     <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                             <div class="relative">
                                <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" placeholder="Search visits..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>

                            <!-- Agent Filter -->
                            <div>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                                    <option value="all">All Agents</option>
                                    <option value="{{ $agent['id'] ?? '' }}">{{ $agent['agentName'] ?? 'Current Agent' }}</option>
                                </select>
                            </div>

                            <!-- Date From -->
                            <div>
                                <input type="date" placeholder="From date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>

                            <!-- Date To -->
                            <div>
                                <input type="date" placeholder="To date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                            </div>
                        </div>

                         <!-- Export Button -->
                        <div class="flex justify-end mt-4">
                            <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2">
                                <i class="bi bi-download"></i> Export to CSV
                            </button>
                        </div>
                    </div>

                    <!-- Visits List -->
                    <div class="space-y-4">
                        @forelse($visits as $visit)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row items-start justify-between gap-4">
                                    <!-- Left: Visit Info -->
                                    <div class="flex-1 w-full">
                                        <div class="flex items-center gap-3 mb-3 flex-wrap">
                                            <h3 class="text-gray-900 font-bold">{{ $visit['visitNumber'] ?? $visit['id'] }}</h3>
                                            @php
                                                $statusColors = [
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'skipped' => 'bg-red-100 text-red-800',
                                                    'in_progress' => 'bg-blue-100 text-blue-800'
                                                ];
                                            @endphp
                                            <span class="px-2 py-0.5 rounded text-xs font-bold uppercase {{ $statusColors[$visit['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $visit['status'])) }}
                                            </span>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3 text-sm">
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="bi bi-calendar"></i>
                                                <span>{{ date('d M Y', strtotime($visit['date'])) }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="bi bi-person"></i>
                                                <span>{{ $visit['agentName'] }}</span>
                                            </div>
                                        </div>

                                        <!-- Time & Duration -->
                                        <div class="flex items-center gap-4 mb-3 text-sm flex-wrap">
                                            @if(isset($visit['checkInTime']))
                                            <div class="flex items-center gap-2 text-gray-600">
                                                <i class="bi bi-clock text-green-600"></i>
                                                <span>In: {{ $visit['checkInTime'] }}</span>
                                            </div>
                                            @endif
                                            @if(isset($visit['checkOutTime']))
                                                <div class="flex items-center gap-2 text-gray-600">
                                                    <i class="bi bi-clock text-red-600"></i>
                                                    <span>Out: {{ $visit['checkOutTime'] }}</span>
                                                </div>
                                                @if(isset($visit['duration']))
                                                <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 text-xs font-medium">
                                                    {{ $visit['duration'] }} min
                                                </span>
                                                @endif
                                            @endif
                                        </div>

                                        <!-- Activity Summary -->
                                        <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                            <div class="grid grid-cols-2 gap-3 text-sm">
                                                <div>
                                                    <div class="text-gray-600 mb-1">Order Placed</div>
                                                    <div class="text-gray-900 font-medium">
                                                        @if(isset($visit['orderPlaced']) && $visit['orderPlaced'])
                                                            Yes - Rs. {{ number_format($visit['orderValue']) }}
                                                        @else
                                                            No
                                                        @endif
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-gray-600 mb-1">Payment Collected</div>
                                                     <div class="text-gray-900 font-medium">
                                                        @if(isset($visit['paymentCollected']) && $visit['paymentCollected'])
                                                            Yes - Rs. {{ number_format($visit['paymentAmount']) }}
                                                        @else
                                                            No
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Skip Reason -->
                                        @if($visit['status'] === 'skipped' && isset($visit['skipReason']))
                                            <div class="bg-red-50 rounded-lg p-3 mb-3 text-sm">
                                                <div class="text-red-900"><span class="font-medium">Skip Reason:</span> {{ $visit['skipReason'] }}</div>
                                            </div>
                                        @endif

                                        <!-- Location -->
                                        @if(isset($visit['location']))
                                            <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
                                                <i class="bi bi-geo-alt text-blue-600"></i>
                                                <span>
                                                    Lat: {{ number_format($visit['location']['latitude'], 4) }}, Lng: {{ number_format($visit['location']['longitude'], 4) }}
                                                </span>
                                                @if(isset($visit['location']['accuracy']))
                                                    <span class="text-xs text-gray-500">(±{{ $visit['location']['accuracy'] }}m)</span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Notes -->
                                        @if(isset($visit['notes']) && $visit['notes'])
                                            <div class="flex items-start gap-2 text-sm text-gray-600 mb-3">
                                                <i class="bi bi-chat-text mt-0.5"></i>
                                                <span>{{ $visit['notes'] }}</span>
                                            </div>
                                        @endif

                                        <!-- Feedback -->
                                        @if(isset($visit['feedback']))
                                            <div class="bg-yellow-50 rounded-lg p-3 text-sm">
                                                 <div class="flex items-center gap-2 mb-2">
                                                    <i class="bi bi-star-fill text-yellow-500"></i>
                                                    <span class="text-yellow-900 font-medium">Customer Feedback</span>
                                                    <span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-800 text-xs font-bold">
                                                        {{ $visit['feedback']['rating'] }} / 5
                                                    </span>
                                                </div>
                                                @if(isset($visit['feedback']['comments']))
                                                    <div class="text-yellow-900 italic">"{{ $visit['feedback']['comments'] }}"</div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Right: Actions -->
                                    <div class="flex flex-col items-end gap-3 min-w-[120px]">
                                        @if(isset($visit['photosTaken']) && $visit['photosTaken'] > 0)
                                            <span class="px-2 py-1 rounded bg-purple-100 text-purple-800 text-xs font-medium flex items-center gap-1">
                                                <i class="bi bi-camera"></i> {{ $visit['photosTaken'] }} Photo{{ $visit['photosTaken'] > 1 ? 's' : '' }}
                                            </span>
                                        @endif

                                        <button class="px-3 py-1.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm flex items-center gap-2 w-full justify-center">
                                            View Details
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                             <div class="p-8 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                                <i class="bi bi-geo-alt text-gray-400 text-4xl mb-3 block"></i>
                                <p class="text-gray-600 font-medium">No visits found</p>
                                <p class="text-sm text-gray-500 mt-1">Try adjusting your filters</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal (Reused) -->
    @include('agentDistribution.Modals.createCustomer')

    <!-- Scripts -->
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        // --- Customer Modal Logic ---
        // Duplicated from customerManagement.blade.php for standalone functionality
        
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
                    document.getElementById('allow-credit').checked = true;
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
                // Create Mode - unlikely here but good for completeness
                document.getElementById('modal-title').textContent = 'Add New Customer';
                document.getElementById('modal-subtitle').textContent = 'Create a new B2B or B2C customer';
                document.getElementById('cust-id').value = '';
                setCustomerType('b2b'); // Default
                document.getElementById('allow-credit').checked = true;
                toggleCredit();
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
            
            // In a real app, this would submit via AJAX or Form
            // Here we just mock the success
            Swal.fire('Updated', 'Customer profile updated successfully', 'success');
            closeCustomerModal();
            // Optional: location.reload() to seeing changes
        }
    </script>
@endsection
