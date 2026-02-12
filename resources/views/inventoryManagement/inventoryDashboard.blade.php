@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Inventory Dashboard</h1>
                <p class="text-gray-600 mt-1">Overview of all inventory across locations</p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.location.reload()"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    <i class="bi bi-arrow-clockwise h-4 w-4 mr-2"></i>
                    Refresh
                </button>
                <a href="{{ route('inventoryMaster.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <i class="bi bi-eye h-4 w-4 mr-2"></i>
                    View All Inventory
                </a>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Total Inventory Value</p>
                        <p class="text-2xl font-bold text-blue-900 mt-2">
                            Rs. {{ number_format($stats['totalInventoryValue']) }}
                        </p>
                        <p class="text-xs text-blue-600 mt-1">Across all locations</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-blue-200 flex items-center justify-center">
                        <i class="bi bi-currency-dollar text-2xl text-blue-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-yellow-600">Low Stock Items</p>
                        <p class="text-2xl font-bold text-yellow-900 mt-2">{{ $stats['lowStockItems'] }}</p>
                        <a href="{{ route('inventoryMaster.index', ['status' => 'low-stock']) }}"
                            class="text-xs text-yellow-700 mt-1 hover:underline block">
                            View items →
                        </a>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-yellow-200 flex items-center justify-center">
                        <i class="bi bi-exclamation-triangle text-2xl text-yellow-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-orange-600">Expiring Soon</p>
                        <p class="text-2xl font-bold text-orange-900 mt-2">{{ $stats['expiringItems'] }}</p>
                        <p class="text-xs text-orange-600 mt-1">Next 7 days</p>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-orange-200 flex items-center justify-center">
                        <i class="bi bi-calendar-event text-2xl text-orange-700"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Pending Transfers</p>
                        <p class="text-2xl font-bold text-purple-900 mt-2">{{ $stats['pendingTransfers'] }}</p>
                        <a href="{{ route('manageStockTransfers.index') }}"
                            class="text-xs text-purple-700 mt-1 hover:underline block">
                            Review now →
                        </a>
                    </div>
                    <div class="h-12 w-12 rounded-full bg-purple-200 flex items-center justify-center">
                        <i class="bi bi-clock-history text-2xl text-purple-700"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
            <h2 class="text-lg font-semibold mb-4 text-gray-900">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="{{ route('createStockTransfer.index') }}"
                    class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <i class="bi bi-arrow-left-right text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Create Transfer</span>
                </a>
                <a href="{{ route('stockAdjustments.index') }}"
                    class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <i class="bi bi-file-text text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Stock Adjustment</span>
                </a>
                <a href="{{ route('createGRN.index') }}"
                    class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <i class="bi bi-cart-plus text-2xl mb-2"></i>
                    <span class="text-sm font-medium">New GRN</span>
                </a>
                <a href="{{ route('createPurchaseOrder.index') }}"
                    class="flex flex-col items-center justify-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <i class="bi bi-box-seam text-2xl mb-2"></i>
                    <span class="text-sm font-medium">Create PO</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Warehouse Status --}}
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-900">
                        <i class="bi bi-building"></i>
                        Warehouse Status
                    </h2>
                    <a href="{{ route('warehouseManagement.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        View All →
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($warehouseStatuses as $warehouse)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-medium text-gray-900">{{ $warehouse['name'] }}</h3>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $warehouse['status'] === 'normal' ? 'bg-green-100 text-green-800' :
                        ($warehouse['status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($warehouse['status']) }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-gray-500">Value</p>
                                            <p class="font-semibold text-gray-900">Rs. {{ number_format($warehouse['value']) }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500">Items</p>
                                            <p class="font-semibold text-gray-900">{{ $warehouse['items'] }}</p>
                                        </div>
                                        @if(isset($warehouse['temperature']))
                                            <div>
                                                <p class="text-gray-500">Temperature</p>
                                                <p class="font-semibold text-gray-900 flex items-center gap-1">
                                                    <i class="bi bi-thermometer-half"></i>
                                                    {{ $warehouse['temperature'] }}°C
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500">Humidity</p>
                                                <p class="font-semibold text-gray-900">{{ $warehouse['humidity'] }}%</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-900">
                        <i class="bi bi-activity"></i>
                        Recent Activity
                    </h2>
                </div>
                <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                    @forelse($stats['recentActivity'] as $activity)
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-b-0">
                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                @if($activity['type'] == 'transfer')
                                    <i class="bi bi-arrow-left-right text-gray-600 text-sm"></i>
                                @elseif($activity['type'] == 'grn')
                                    <i class="bi bi-cart-check text-gray-600 text-sm"></i>
                                @elseif($activity['type'] == 'adjustment')
                                    <i class="bi bi-file-earmark-text text-gray-600 text-sm"></i>
                                @else
                                    <i class="bi bi-box text-gray-600 text-sm"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $activity['description'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    by {{ $activity['user'] }} •
                                    {{ \Carbon\Carbon::parse($activity['timestamp'])->format('d/m/Y h:i A') }}
                                </p>
                            </div>
                            @if(isset($activity['value']))
                                <div class="text-right flex-shrink-0">
                                    <p
                                        class="text-sm font-semibold {{ $activity['value'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $activity['value'] >= 0 ? '+' : '' }}Rs. {{ number_format(abs($activity['value'])) }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center py-8">No recent activity</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection