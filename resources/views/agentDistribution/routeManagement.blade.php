@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-sign-turn-right text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Route Management</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Define routes and assign agents</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <button onclick="openRouteModal()"
                        class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors">
                        <i class="bi bi-plus-lg mr-2"></i>
                        Add Route
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Actions -->
        <div class="p-6 max-w-[1800px] mx-auto">

            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4 justify-between">
                    <div class="flex-1 max-w-full">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="searchInput" placeholder="Search routes..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Routes Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="routesGrid">
                @forelse($routes as $route)
                    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow route-card"
                        data-search="{{ strtolower($route['routeName'] . ' ' . $route['routeCode']) }}">
                        <!-- Route Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-gray-900 font-bold text-lg">{{ $route['routeName'] }}</h3>
                                    @if($route['status'] == 1)
                                        <span
                                            class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-medium">Active</span>
                                    @else
                                        <span
                                            class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-medium">Inactive</span>
                                    @endif
                                </div>
                                <p class="text-gray-600 text-sm font-mono">{{ $route['routeCode'] }}</p>
                            </div>
                        </div>

                        <!-- Route Details -->
                        <div class="space-y-2 mb-4 text-gray-600 text-sm">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-person text-gray-400"></i>
                                <span>Agent: {{ $route['agentName'] ?? 'Not Assigned' }}</span>
                            </div>
                            @if($route['target_distance_km'])
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-geo-alt text-gray-400"></i>
                                    <span>{{ $route['target_distance_km'] }} km</span>
                                </div>
                            @endif
                            @if($route['description'])
                                <p class="text-gray-500 mt-2 text-xs italic line-clamp-2">{{ $route['description'] }}</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 pt-4 border-t border-gray-200">
                            <a href="/route-management/{{ $route['id'] }}/builder"
                                class="flex-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-3 py-1.5 rounded text-sm flex items-center justify-center transition-colors">
                                <i class="bi bi-cursor mr-2"></i> Build
                            </a>
                            <button onclick="editRoute('{{ json_encode($route) }}')"
                                class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 px-3 py-1.5 rounded text-sm transition-colors">
                                <i class="bi bi-pencil mr-1"></i> Edit
                            </button>
                            @if($route['status'] == 1)
                                <button onclick="deactivateRoute({{ $route['id'] }}, '{{ $route['routeName'] }}')"
                                    class="bg-white hover:bg-red-50 text-red-600 border border-gray-300 px-3 py-1.5 rounded text-sm transition-colors"
                                    title="Deactivate">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg border border-gray-200 p-12 text-center" id="noRoutesMsg">
                        <i class="bi bi-geo-alt text-gray-400 text-5xl mb-4 block"></i>
                        <h3 class="text-gray-900 text-lg font-medium mb-2">No routes found</h3>
                        <p class="text-gray-600 mb-4">Create your first route to start managing deliveries</p>
                        <button onclick="openRouteModal()"
                            class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg inline-flex items-center shadow-sm transition-colors">
                            <i class="bi bi-plus-lg mr-2"></i>
                            Create Route
                        </button>
                    </div>
                @endforelse

                <!-- JavaScript Search No Results (Hidden by default) -->
                <div id="noSearchResults"
                    class="hidden col-span-full bg-white rounded-lg border border-gray-200 p-12 text-center">
                    <i class="bi bi-search text-gray-400 text-5xl mb-4 block"></i>
                    <h3 class="text-gray-900 text-lg font-medium mb-2">No matching routes</h3>
                    <p class="text-gray-600">Try adjusting your search terms</p>
                </div>
            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div id="route-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div id="route-backdrop"
                class="fixed inset-0 bg-gray-900/75 transition-opacity opacity-0 transition-opacity duration-300 ease-out"
                onclick="closeRouteModal()"></div>

            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div id="route-panel"
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out">

                    <form id="route-form" onsubmit="event.preventDefault(); submitRouteForm();">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Create New
                                        Route</h3>
                                    <p class="text-sm text-gray-500 mb-4" id="modal-desc">Define a new distribution route
                                    </p>

                                    <div class="space-y-4">
                                        <!-- Hidden ID field for edit -->
                                        <input type="hidden" id="routeId">

                                        <div>
                                            <label for="routeName" class="block text-sm font-medium text-gray-700">Route
                                                Name *</label>
                                            <input type="text" id="routeName"
                                                class="mt-1 block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                                placeholder="e.g., Colombo Central Route" required>
                                        </div>

                                        <div>
                                            <label for="description"
                                                class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea id="description" rows="3"
                                                class="mt-1 block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                                placeholder="Route coverage details..."></textarea>
                                        </div>

                                        <div>
                                            <label for="agentId" class="block text-sm font-medium text-gray-700">Assigned
                                                Agent</label>
                                            <select id="agentId"
                                                class="mt-1 block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                                <option value="">Not assigned</option>
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent['id'] }}">{{ $agent['agentName'] }}
                                                        ({{ $agent['agentCode'] }})</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label for="estimatedDistanceKm"
                                                    class="block text-sm font-medium text-gray-700">Distance (km)</label>
                                                <input type="number" step="0.01" id="estimatedDistanceKm"
                                                    class="mt-1 block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                                    placeholder="25">
                                            </div>
                                            <div>
                                                <label for="estimatedDurationHours"
                                                    class="block text-sm font-medium text-gray-700">Duration (hours)</label>
                                                <input type="number" step="0.1" id="estimatedDurationHours"
                                                    class="mt-1 block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm"
                                                    placeholder="4">
                                            </div>
                                        </div>

                                        <div id="statusField" class="hidden">
                                            <label for="status"
                                                class="block text-sm font-medium text-gray-700">Status</label>
                                            <select id="status"
                                                class="mt-1 block w-full p-2 border rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                                <option value="1">Active</option>
                                                <option value="2">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" id="submitBtn"
                                class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto">Create
                                Route</button>
                            <button type="button" onclick="closeRouteModal()"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    <script>
        const modal = document.getElementById('route-modal');
        const backdrop = document.getElementById('route-backdrop');
        const panel = document.getElementById('route-panel');
        const modalTitle = document.getElementById('modal-title');
        const modalDesc = document.getElementById('modal-desc');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('route-form');

        function openRouteModal(isEdit = false) {
            modal.classList.remove('hidden');
            void modal.offsetWidth; // Trigger reflow

            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');

            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            panel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');

            if (!isEdit) {
                form.reset();
                document.getElementById('routeId').value = '';
                document.getElementById('statusField').classList.add('hidden');
                modalTitle.innerText = 'Create New Route';
                modalDesc.innerText = 'Define a new distribution route';
                submitBtn.innerText = 'Create Route';
            } else {
                document.getElementById('statusField').classList.remove('hidden');
            }
        }

        function closeRouteModal() {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');

            panel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function editRoute(routeJson) {
            const route = JSON.parse(routeJson);

            document.getElementById('routeId').value = route.id;
            document.getElementById('routeName').value = route.routeName || route.route_name || '';
            document.getElementById('description').value = route.description || '';
            document.getElementById('agentId').value = route.agentId || route.agent_id || '';
            // Map backend fields to frontend inputs
            document.getElementById('estimatedDistanceKm').value = route.target_distance_km || '';
            document.getElementById('estimatedDurationHours').value = route.target_duration_hours || '';
            document.getElementById('status').value = route.status || 1;

            modalTitle.innerText = 'Edit Route';
            modalDesc.innerText = 'Update route information';
            submitBtn.innerText = 'Update Route';

            openRouteModal(true);
        }

        function submitRouteForm() {
            const routeId = document.getElementById('routeId').value;
            const isEdit = !!routeId;

            const formData = {
                route_name: document.getElementById('routeName').value,
                description: document.getElementById('description').value,
                agent_id: document.getElementById('agentId').value || null,
                // Map frontend inputs to backend fields
                target_distance_km: document.getElementById('estimatedDistanceKm').value || null,
                target_duration_hours: document.getElementById('estimatedDurationHours').value || null,
                status: isEdit ? document.getElementById('status').value : 1
            };

            const url = isEdit ? `/api/routes/${routeId}/update` : '/api/routes/create';
            const method = isEdit ? 'PUT' : 'POST';

            // Show loading
            const originalBtnText = submitBtn.innerText;
            submitBtn.disabled = true;
            submitBtn.innerText = 'Processing...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            closeRouteModal();
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'Something went wrong');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to save route'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerText = originalBtnText;
                });
        }

        function deactivateRoute(id, name) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you really want to deactivate route "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/routes/${id}/deactivate`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Deactivated!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                error.message || 'Failed to deactivate route',
                                'error'
                            );
                        });
                }
            });
        }

        // Search Logic
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const searchText = this.value.toLowerCase();
            const cards = document.querySelectorAll('.route-card');
            let hasVisible = false;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData.includes(searchText)) {
                    card.style.display = 'block';
                    hasVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            const noResults = document.getElementById('noSearchResults');
            if (cards.length > 0) {
                if (!hasVisible) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeRouteModal();
            }
        });
    </script>

    {{-- Google Maps API for Distance Matrix and visual route builder --}}
    <script>
        // Load Google Maps API
        (function () {
            const apiKey = 'AIzaSyDyD-Z0FCjm3sgq4hhNqgZfhiKjOWNuuXw';
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places,geometry`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        })();
    </script>
@endsection