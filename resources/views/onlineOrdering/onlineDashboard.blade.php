@extends('layouts.app')

@section('content')
<div class="space-y-6 p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Online Ordering Dashboard</h1>
            <p class="text-gray-600 mt-1">Manage your online shop and customer orders</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.open('/shop', '_blank')" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors gap-2">
                {{-- Eye Icon --}}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Preview Website
                {{-- ExternalLink Icon --}}
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </button>
            <a href="{{ route('onlineOrderSettings.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 transition-colors gap-2">
                {{-- Settings Icon --}}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </div>
    </div>

    {{-- Online Ordering Status Banner --}}
    @if(!$config->enabled)
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1">
                    <h3 class="font-semibold text-orange-900">Online Ordering is Disabled</h3>
                    <p class="text-sm text-orange-700 mt-1">
                        Customers cannot place orders on your website. Enable it in settings to start accepting online orders.
                    </p>
                    <a href="{{ route('onlineOrderSettings.index') }}" class="mt-3 inline-flex items-center justify-center px-3 py-1.5 border border-orange-300 text-sm font-medium rounded-md text-orange-700 bg-transparent hover:bg-orange-100 transition-colors">
                        Go to Settings
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Today's Orders --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 pb-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-600">Today's Orders</h3>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-gray-900">{{ $analytics->todayOrders }}</div>
                <p class="text-xs text-gray-500 mt-1">Orders placed today</p>
            </div>
        </div>

        {{-- Today's Revenue --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 pb-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-600">Today's Revenue</h3>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-gray-900">
                    Rs {{ number_format($analytics->todayRevenue, 2) }}
                </div>
                <p class="text-xs text-gray-500 mt-1">Online sales today</p>
            </div>
        </div>

        {{-- Average Order Value --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 pb-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-600">Average Order Value</h3>
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                </div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-gray-900">
                    Rs {{ number_format($analytics->averageOrderValue, 2) }}
                </div>
                <p class="text-xs text-gray-500 mt-1">Per order</p>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="p-6 pb-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-600">Pending Orders</h3>
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                         <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>
            <div class="p-6 pt-0">
                <div class="text-2xl font-bold text-gray-900">{{ $analytics->pendingOrders }}</div>
                <p class="text-xs text-gray-500 mt-1">Awaiting confirmation</p>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-lg">Recent Online Orders</h3>
                <p class="text-sm text-gray-500">Latest orders from your online shop</p>
            </div>
            <a href="{{ route('onlineOrderManagement.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors gap-2">
                View All Orders
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
        <div class="p-6">
            @if(count($recentOrders) === 0)
                <div class="text-center py-12">
                     <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <h3 class="font-medium text-gray-900 mb-1">No orders yet</h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Orders from your online shop will appear here
                    </p>
                    <a href="#" onclick="window.open('/shop', '_blank')" class="inline-flex items-center justify-center px-4 py-2 border border-blue-200 text-sm font-medium rounded-md text-blue-700 bg-blue-50 hover:bg-blue-100 transition-colors gap-2">
                         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Online Shop
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @php
                       $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'preparing' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'ready' => 'bg-green-100 text-green-800 border-green-200',
                            'completed' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                            'refunded' => 'bg-orange-100 text-orange-800 border-orange-200',
                        ];
                    @endphp
                    @foreach($recentOrders as $order)
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                             onclick="window.location.href='{{ route('onlineOrderManagement.index') }}'">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="font-semibold text-gray-900">{{ $order->orderNumber }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border flex items-center {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $order->customer->name }} • {{ $order->pickup->outletName }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ count($order->items) }} item{{ count($order->items) !== 1 ? 's' : '' }} • 
                                    Pickup: {{ \Carbon\Carbon::parse($order->pickup->scheduledDate)->format('d/m/Y') }} {{ $order->pickup->scheduledTime }}
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-gray-900">
                                    Rs {{ number_format($order->summary->total, 2) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($order->createdAt)->format('h:i A') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('onlineOrderManagement.index') }}'">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="font-semibold text-lg mb-1">Manage Orders</h3>
            <p class="text-sm text-gray-500">View and process online orders from customers</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('productManagement.index') }}'">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3">
                 <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="font-semibold text-lg mb-1">Product Catalog</h3>
            <p class="text-sm text-gray-500">Manage products available for online ordering</p>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow cursor-pointer" onclick="window.location.href='{{ route('customerManagement.index') }}'">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3">
                 <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <h3 class="font-semibold text-lg mb-1">Customers</h3>
            <p class="text-sm text-gray-500">View customer accounts and order history</p>
        </div>
    </div>
</div>
@endsection