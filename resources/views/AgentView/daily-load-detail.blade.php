@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#EDEFF5]">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('agent-panel.daily-loads') }}" class="text-gray-500 hover:text-gray-800 transition-colors">
                        <i class="bi bi-arrow-left text-2xl"></i>
                    </a>
                    <div class="min-w-0 flex-1">
                        <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Daily Load Detail - LOAD-{{ $load->id }}</h1>
                        <p class="text-gray-500 text-xs sm:text-sm">Manage trip status, verify stock, and perform unloading.</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                @php
                    $statusClasses = [
                        1 => 'bg-amber-100 text-amber-800 border-amber-200',
                        2 => 'bg-blue-100 text-blue-800 border-blue-200',
                        3 => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                        4 => 'bg-purple-100 text-purple-800 border-purple-200',
                        5 => 'bg-emerald-100 text-emerald-800 border-emerald-200'
                    ];
                    $statusLabels = [
                        1 => 'Loading / Draft',
                        2 => 'Loaded',
                        3 => 'Started / On Trip',
                        4 => 'Unloaded',
                        5 => 'Finished'
                    ];
                @endphp
                <span class="px-3 py-1.5 rounded-full text-sm font-semibold border {{ $statusClasses[$load->load_status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                    Status: {{ $statusLabels[$load->load_status] ?? 'Unknown' }}
                </span>
            </div>
        </div>
    </div>

    <div class="p-6 max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- LEFT PANEL: Info & Actions -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-gray-900 font-bold text-lg border-b pb-3 mb-4">Trip Details</h3>
                <div class="space-y-4 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Route:</span>
                        <span class="font-semibold text-gray-900">{{ $load->route ? $load->route->route_name : 'No Route Assigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Driver:</span>
                        <span class="font-semibold text-gray-900">{{ $load->driver ? $load->driver->driver_name : 'Unassigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Vehicle:</span>
                        <span class="font-semibold text-gray-900">{{ $load->vehicle ? $load->vehicle->vehicle_number : 'Unassigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Supervisor:</span>
                        <span class="font-semibold text-gray-900">{{ $load->supervisor ? $load->supervisor->superviser_name : 'Unassigned' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Load Date:</span>
                        <span class="font-semibold text-gray-900">{{ $load->load_date->format('M d, Y') }}</span>
                    </div>
                    <div class="border-t pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-500">Starting Mileage:</span>
                            <span class="font-semibold text-gray-900">{{ $load->starting_mileage ? $load->starting_mileage . ' km' : 'Not Recorded' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ending Mileage:</span>
                            <span class="font-semibold text-gray-900">{{ $load->ending_mileage ? $load->ending_mileage . ' km' : 'Not Recorded' }}</span>
                        </div>
                    </div>
                    @if($load->notes)
                        <div class="border-t pt-4">
                            <span class="text-gray-500 block mb-1">Notes:</span>
                            <p class="text-gray-600 bg-gray-50 p-3 rounded-lg text-xs italic">{{ $load->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action panel -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-gray-900 font-bold text-lg border-b pb-3 mb-4">Actions</h3>
                
                @if($load->load_status === 1)
                    <!-- Draft: Mark as Loaded -->
                    <button onclick="updateLoadStatus('mark_as_loaded')"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow transition-colors flex items-center justify-center gap-2">
                        <i class="bi bi-check-circle"></i> Mark as Loaded
                    </button>
                @elseif($load->load_status === 2)
                    <!-- Loaded: Start Trip -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Starting Mileage (KM)</label>
                            <input type="number" id="starting_mileage_input" placeholder="e.g. 15420"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-gray-50 font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Trip Notes</label>
                            <textarea id="trip_notes_input" placeholder="e.g. Weather conditions, special instructions..." rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-gray-50 resize-none"></textarea>
                        </div>
                        <button onclick="startTrip()"
                                class="w-full bg-[#059669] hover:bg-[#047857] text-white font-bold py-3 px-4 rounded-xl shadow transition-colors flex items-center justify-center gap-2">
                            <i class="bi bi-play-fill"></i> Start Trip
                        </button>
                    </div>
                @elseif($load->load_status === 3)
                    <!-- On Trip: Finish Load -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Ending Mileage (KM)</label>
                            <input type="number" id="ending_mileage_input" placeholder="e.g. 15540"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-gray-50 font-medium">
                        </div>
                        <button onclick="finishTrip()"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow transition-colors flex items-center justify-center gap-2">
                            <i class="bi bi-flag-fill"></i> Finish Trip & Unload
                        </button>
                    </div>
                @else
                    <div class="p-4 bg-gray-50 rounded-xl text-center text-sm text-gray-500 italic">
                        No actions available. Load has been successfully finished.
                    </div>
                @endif
            </div>
        </div>

        <!-- RIGHT PANEL: Products Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="text-gray-900 font-bold text-lg flex items-center gap-2">
                        <i class="bi bi-box-seam text-indigo-600"></i> Load Items
                    </h3>
                    <span class="text-xs text-gray-500">{{ count($load->items) }} Products loaded</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold text-gray-500 uppercase tracking-wider text-xs">Product</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Price</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Loaded Qty</th>
                                @if($load->load_status >= 3)
                                    <th class="px-6 py-4 text-center font-bold text-gray-500 uppercase tracking-wider text-xs">Unload Qty</th>
                                @endif
                                <th class="px-6 py-4 text-right font-bold text-gray-500 uppercase tracking-wider text-xs">Total Value</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($load->items as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                        {{ $item->product ? $item->product->product_name : 'Unknown Product' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-600">
                                        Rs. {{ number_format($item->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-900 font-bold">
                                        {{ number_format($item->loaded_qty ?? $item->quantity) }}
                                    </td>
                                    @if($load->load_status == 3)
                                        <!-- Edit Unload Qty -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <input type="number" step="1" min="0" max="{{ $item->loaded_qty ?? $item->quantity }}"
                                                   data-id="{{ $item->product_item_id }}"
                                                   value="{{ $item->unload_qty ?? 0 }}"
                                                   class="unload-qty-input w-20 px-2 py-1 text-center border rounded-lg focus:ring-2 focus:ring-indigo-500 font-semibold text-gray-900">
                                        </td>
                                    @elseif($load->load_status > 3)
                                        <!-- Read Only Unload Qty -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-gray-900 font-semibold">
                                            {{ number_format($item->unload_qty) }}
                                        </td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-[#059669]">
                                        Rs. {{ number_format($item->total_value, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="bg-gray-50 px-6 py-5 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-700">Total Load Value</span>
                <span class="text-xl font-extrabold text-[#059669]">Rs. {{ number_format($load->items->sum('total_value'), 2) }}</span>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function updateLoadStatus(action) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to proceed with this status update?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("agent-panel.daily-loads.update-status", $load->id) }}',
                    method: 'POST',
                    data: {
                        action: action,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success!', response.message, 'success').then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }

    function startTrip() {
        const mileage = $('#starting_mileage_input').val();
        const notes = $('#trip_notes_input').val();

        if (!mileage) {
            Swal.fire('Warning', 'Please enter starting mileage before starting the trip.', 'warning');
            return;
        }

        $.ajax({
            url: '{{ route("agent-panel.daily-loads.update-status", $load->id) }}',
            method: 'POST',
            data: {
                action: 'start_trip',
                starting_mileage: mileage,
                notes: notes,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Trip Started!', response.message, 'success').then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.', 'error');
            }
        });
    }

    function finishTrip() {
        const endingMileage = $('#ending_mileage_input').val();
        if (!endingMileage) {
            Swal.fire('Warning', 'Please enter ending mileage before finishing the trip.', 'warning');
            return;
        }

        const items = [];
        let valid = true;

        $('.unload-qty-input').each(function() {
            const input = $(this);
            const itemId = input.data('id');
            const unloadQty = parseFloat(input.val() || 0);
            const maxVal = parseFloat(input.attr('max'));

            if (unloadQty > maxVal) {
                Swal.fire('Warning', `Unload quantity cannot exceed loaded quantity (${maxVal}).`, 'warning');
                valid = false;
                return false;
            }

            items.push({
                product_item_id: itemId,
                unload_qty: unloadQty
            });
        });

        if (!valid) return;

        Swal.fire({
            title: 'Finish Trip?',
            text: "Are you sure you want to finish this trip and record unloading details?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Finish Trip!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("agent-panel.daily-loads.finish", $load->id) }}',
                    method: 'POST',
                    data: {
                        ending_mileage: endingMileage,
                        items: items,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Trip Finished!', response.message, 'success').then(() => location.reload());
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.', 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
