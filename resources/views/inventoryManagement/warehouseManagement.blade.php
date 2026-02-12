@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6 bg-gray-50 min-h-screen">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Warehouse Management</h1>
            <p class="text-gray-600 mt-1">Manage inventory in warehouses under your control</p>
        </div>
        <div class="flex gap-2">
            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Refresh
            </button>
            <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export
            </button>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-4">
            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <select name="warehouse" onchange="this.form.submit()" class="block p-3 bg-gray-50 w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="main-warehouse" selected>Main Warehouse</option>
                <option value="dry-storage">Dry Storage</option>
                <option value="freezer-1">Freezer 1 (-18°C)</option>
                <option value="freezer-2">Freezer 2 (-5°C)</option>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Value</p>
                <p class="text-2xl font-bold mt-1 text-gray-900">Rs. 1,200,000</p>
            </div>
            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>

        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Items</p>
                <p class="text-2xl font-bold mt-1 text-gray-900">480</p>
            </div>
            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V4"></path></svg>
        </div>

        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
            <div class="w-full mr-4">
                <p class="text-sm text-gray-600 font-medium">Capacity Used</p>
                <p class="text-2xl font-bold mt-1 text-gray-900">48%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: 48%"></div>
                </div>
            </div>
            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>

        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 font-medium">Status</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                    Normal
                </span>
            </div>
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
        <h2 class="text-lg font-semibold mb-4 flex items-center gap-2 text-gray-800">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Environmental Monitoring
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Temperature</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Normal</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900">22°C</span>
                    <span class="text-sm text-gray-500 text-gray-500">Target: 22°C</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 50%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Humidity</span>
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Warning</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900">45%</span>
                    <span class="text-sm text-gray-500">Target: 40%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <a href="#" class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <span class="text-sm font-medium">Transfer Out</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <span class="text-sm font-medium">Stock Adjustment</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <span class="text-sm font-medium">Cycle Count</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h2M4 8h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                <span class="text-sm font-medium">Stock Take</span>
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
        <div class="flex flex-col md:flex-row items-center justify-between mb-4 gap-4">
            <h2 class="text-lg font-semibold text-gray-800">Products in Main Warehouse</h2>
            <div class="flex gap-2 w-full md:w-auto">
                <input type="text" placeholder="Search products..." class="block p-3 bg-gray-50 w-full md:w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <select class="block p-3 bg-gray-50 w-full md:w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="all">All Status</option>
                    <option value="normal">Normal</option>
                    <option value="expiring">Expiring</option>
                    <option value="damaged">Damaged</option>
                </select>
            </div>
        </div>

        <div class="border border-gray-200 rounded-lg overflow-hidden overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="p-3 text-sm font-semibold text-gray-700">Product</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Location</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Batch</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Quantity</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Expiry</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Value</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Status</th>
                        <th class="p-3 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">
                            <div class="font-semibold text-gray-900">All-Purpose Flour</div>
                            <div class="text-xs text-gray-500">RM-FLOUR-001</div>
                        </td>
                        <td class="p-3">
                            <div class="flex items-center gap-1 text-sm text-gray-600">
                                <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-width="2"></path></svg>
                                Shelf A1
                            </div>
                        </td>
                        <td class="p-3 text-sm font-mono text-gray-600">B001</td>
                        <td class="p-3 font-semibold text-gray-900">500 kg</td>
                        <td class="p-3 text-sm text-gray-600">20/12/2026</td>
                        <td class="p-3 font-semibold text-gray-900">Rs. 75,000</td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Normal</span>
                        </td>
                        <td class="p-3">
                            <div class="flex gap-1">
                                <button class="p-1 text-gray-400 hover:text-gray-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"></path></svg></button>
                                <button class="p-1 text-gray-400 hover:text-gray-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"></path></svg></button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>
@endsection