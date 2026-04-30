@extends('layouts.app')

@section('title', 'Command Center')

@section('content')

    <div class="grid grid-cols-1 gap-6 w-full">

        <nav class="col-span-1 flex justify-between items-center mb-2">
            <div class="page-title">
                <h4 class="font-bold text-2xl text-gray-800 m-0">@yield('title', 'Dashboard')</h4>
                <p class="text-gray-500 text-sm m-0">{{ now()->format('l, F j, Y') }} • Real-time Overview</p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Export Report
                </button>

                <button
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg hover:bg-emerald-100 text-sm font-medium transition-colors shadow-sm">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    System Online
                </button>
            </div>
        </nav>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Today's Revenue -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600">
                        <line x1="12" y1="1" x2="12" y2="23"></line>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Today's Revenue</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-1">Rs {{ number_format($todayRevenue, 2) }}</h2>
                    <div class="flex items-center mt-4 text-xs font-semibold text-emerald-600 bg-emerald-50 self-start px-2 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                            <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                            <polyline points="17 6 23 6 23 12"></polyline>
                        </svg>
                        Live Tracking
                    </div>
                </div>
            </div>

            <!-- Today's Orders -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Today's Orders</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $todayOrdersCount }}</h2>
                    <div class="flex items-center mt-4 text-xs font-semibold text-blue-600 bg-blue-50 self-start px-2 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Latest Update
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600">
                        <path d="M17 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M9 21v-2a4 4 0 0 1 3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        <path d="M8 3.13a4 4 0 0 0 0 7.75"></path>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Customers</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $totalCustomersCount }}</h2>
                    <div class="flex items-center mt-4 text-xs font-semibold text-purple-600 bg-purple-50 self-start px-2 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <polyline points="16 11 18 13 22 9"></polyline>
                        </svg>
                        Active Database
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                        <line x1="12" y1="9" x2="12" y2="13"></line>
                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Agents</span>
                    <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ $agentCount }}</h2>
                    <div class="flex items-center mt-4 text-xs font-semibold text-red-600 bg-red-50 self-start px-2 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                        All Agents
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Orders Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h5 class="text-lg font-bold text-gray-900 m-0">Recent Orders</h5>
                        <p class="text-sm text-gray-500 m-0">Latest transactions from across the system</p>
                    </div>
                    <a href="{{ route('order-management.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">View All →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Order #</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Customer / Agent</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Amount</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-900">{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-gray-800 text-sm">
                                                {{ $order->customer->name ?? ($order->agent->agent_name ?? 'Walk-in Customer') }}
                                            </span>
                                            <span class="text-xs text-gray-400">
                                                {{ $order->customer->phone ?? ($order->agent->phone ?? '-') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-900">Rs {{ number_format($order->grand_total, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $order->status_color }}">
                                            {{ $order->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs text-gray-500 font-medium">{{ $order->created_at->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-gray-300 mb-2">
                                                <rect width="18" height="18" x="3" y="3" rx="2"></rect>
                                                <path d="M3 9h18"></path>
                                                <path d="M9 21V9"></path>
                                            </svg>
                                            <p class="font-medium">No orders found for today yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock List -->
            {{-- <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h5 class="text-lg font-bold text-gray-900 m-0 text-red-600">Stock Alerts</h5>
                        <p class="text-sm text-gray-500 m-0">Critical inventory levels</p>
                    </div>
                </div>
                <div class="flex-1 p-6 flex flex-col gap-4">
                    @forelse($lowStockItems as $stock)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-red-50/50 border border-red-100/50 group hover:border-red-200 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white shadow-sm border border-red-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600">
                                        <path d="m7.5 4.27 9 5.15"></path>
                                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path>
                                        <path d="m3.3 7 8.7 5 8.7-5"></path>
                                        <path d="M12 22V12"></path>
                                    </svg>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800 text-sm group-hover:text-red-700 transition-colors">{{ $stock->productItem->product_name ?? 'Unknown Item' }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">SKU: {{ $stock->productItem->id }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-black text-red-600">{{ number_format($stock->quantity, 0) }}</div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase">Left</div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full py-10 opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 mb-2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            <p class="font-bold text-gray-500">Stock levels are healthy!</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-4 border-t border-gray-50 mt-auto">
                    <a href="{{ route('inventoryManagement.index') }}" class="w-full flex items-center justify-center gap-2 py-3 bg-gray-900 text-white rounded-xl font-bold text-sm hover:bg-black transition-colors shadow-lg">
                        Restock Inventory
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </a>
                </div>
            </div> --}}
        </div>

    </div>
@endsection</div>

            

            

        </div>
    {{-- @endsection --}}
