@extends('layouts.app')

@section('content')
    <div class="p-6 space-y-6 bg-gray-50 min-h-screen">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Section & Outlet Inventory</h1>
                <p class="text-gray-600 mt-1">View and manage inventory across all locations</p>
            </div>
            <div class="flex gap-2">
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none disabled:opacity-25 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
                <button
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none disabled:opacity-25 transition">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
            <div class="flex items-center gap-4">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <select id="departmentSelect"
                    class="block p-3 w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select a Section / Outlet</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-blue-600 font-medium">Stock Value</p>
                    <p class="text-2xl font-bold text-blue-900 mt-1" id="statStockValue">Rs. 0</p>
                </div>
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-600 font-medium">Items</p>
                    <p class="text-2xl font-bold text-green-900 mt-1" id="statItemCount">0</p>
                </div>
                <i class="bi bi-box text-2xl text-green-400"></i>
            </div>
            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-yellow-600 font-medium">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-900 mt-1" id="statLowStock">0</p>
                </div>
                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-center justify-between">
                <div>
                    <p class="text-sm text-red-600 font-medium">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-900 mt-1" id="statOutOfStock">0</p>
                </div>
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm mb-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Quick Actions</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <a href="#"
                    class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Request Stock</span>
                </a>
                <a href="#"
                    class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    <span class="text-sm font-medium">Transfer Stock</span>
                </a>
                <a href="#"
                    class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">Stock Adjustment</span>
                </a>
                <a href="#"
                    class="flex flex-col items-center justify-center py-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-700">
                    <svg class="h-6 w-6 mb-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium">View History</span>
                </a>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" placeholder="Search products..."
                        class="block p-3 bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <select
                    class="block p-3 bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option>All Status</option>
                    <option>In Stock</option>
                    <option>Low Stock</option>
                    <option>Out of Stock</option>
                </select>
                <select
                    class="block p-3 bg-gray-50 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option>All Categories</option>
                    <option>Raw Materials</option>
                    <option>Finished Goods</option>
                </select>
            </form>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Reorder Point</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="inventoryTableBody">
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">Please select a department to view
                                inventory.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#departmentSelect').change(function () {
                var deptId = $(this).val();

                if (!deptId) {
                    $('#inventoryTableBody').html('<tr><td colspan="7" class="p-4 text-center text-gray-500">Please select a department to view inventory.</td></tr>');
                    resetStats();
                    return;
                }

                // Show loading
                $('#inventoryTableBody').html('<tr><td colspan="7" class="p-4 text-center text-gray-400">Loading...</td></tr>');

                $.ajax({
                    url: "{{ route('inventory.department.stock') }}",
                    method: "POST",
                    data: {
                        department_id: deptId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        renderTable(response.items);
                        updateStats(response.stats);
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        $('#inventoryTableBody').html('<tr><td colspan="7" class="p-4 text-center text-red-500">Error loading data. Please try again.</td></tr>');
                        resetStats();
                    }
                });
            });

            function renderTable(items) {
                var html = '';

                if (items.length === 0) {
                    html = '<tr><td colspan="7" class="p-4 text-center text-gray-500">No stock items found for this department.</td></tr>';
                } else {
                    items.forEach(function (item) {
                        // Calculate progress bar width
                        var progress = 0;
                        if (item.reorder_point > 0) {
                            progress = (item.quantity / (item.reorder_point * 2)) * 100; // Arbitrary scale, double reorder point as "full"ish
                            if (progress > 100) progress = 100;
                        }

                        html += `
                                <tr class="hover:bg-gray-50">
                                    <td class="p-4">
                                        <div class="font-bold text-gray-900">${item.name}</div>
                                        <div class="text-xs text-gray-500">${item.code || 'N/A'}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 text-xs font-medium border border-gray-200 rounded-full text-gray-600">${item.category}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-semibold text-gray-900">${item.quantity} ${item.unit}</div>
                                        <div class="w-20 bg-gray-200 rounded-full h-1 mt-2">
                                            <div class="${getStatusColor(item.status)} h-1 rounded-full" style="width: ${progress}%"></div>
                                        </div>
                                    </td>
                                    <td class="p-4 text-sm text-gray-600">${item.reorder_point} ${item.unit}</td>
                                    <td class="p-4 font-semibold text-gray-900">Rs. ${parseFloat(item.value).toLocaleString()}</td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${item.status_class}">
                                            ${item.status}
                                        </span>
                                    </td>
                                    <td class="p-4 flex gap-2">
                                        <button class="p-1 text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                        <button class="p-1 text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                    </td>
                                </tr>
                             `;
                    });
                }

                $('#inventoryTableBody').html(html);
            }

            function updateStats(stats) {
                $('#statStockValue').text('Rs. ' + parseFloat(stats.stock_value).toLocaleString());
                $('#statItemCount').text(stats.item_count);
                $('#statLowStock').text(stats.low_stock);
                $('#statOutOfStock').text(stats.out_of_stock);
            }

            function resetStats() {
                $('#statStockValue').text('Rs. 0');
                $('#statItemCount').text('0');
                $('#statLowStock').text('0');
                $('#statOutOfStock').text('0');
            }

            function getStatusColor(status) {
                if (status === 'Out of Stock') return 'bg-red-500';
                if (status === 'Low Stock') return 'bg-yellow-500';
                return 'bg-green-500';
            }
        });
    </script>

@endsection