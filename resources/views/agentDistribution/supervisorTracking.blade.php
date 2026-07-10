@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm text-white">
                            <i class="bi bi-geo-fill text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Supervisor Tracking</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Real-time mapping and history visualizer for route supervisors</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <button onclick="openAllSupervisorsMap()"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors text-sm font-medium">
                        <i class="bi bi-geo-alt mr-2"></i>
                        View on Map
                    </button>
                    
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
            <!-- Filters and Search -->
            <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="supervisorSearch" placeholder="Search supervisors by name or code..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <select id="agentFilter"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="all">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->agent_name }} ({{ $agent->agent_code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex justify-between items-center mb-4">
                <div class="text-gray-600 text-sm">
                    Showing <span id="supervisorCount">{{ count($supervisors) }}</span> supervisor(s)
                </div>
            </div>

            <!-- Supervisors Table -->
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                            <tr>
                                <th scope="col" class="px-6 py-3">Supervisor Code</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Assigned Agent</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Contact</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="supervisorsTableBody">
                            @forelse($supervisors as $supervisor)
                                <tr class="bg-white hover:bg-gray-50 supervisor-row"
                                    data-search="{{ strtolower($supervisor['superviser_name'] . ' ' . $supervisor['superviser_code']) }}"
                                    data-agent-id="{{ $supervisor['agent_id'] ?? 'none' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $supervisor['superviser_code'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $supervisor['superviser_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $supervisor['agent_name'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusLabels = [1 => 'Active', 2 => 'Inactive'];
                                            $statusColors = [1 => 'bg-green-100 text-green-800', 2 => 'bg-red-100 text-red-800'];
                                        @endphp
                                        <span class="{{ $statusColors[$supervisor['status']] ?? 'bg-gray-100 text-gray-800' }} text-xs px-2.5 py-0.5 rounded font-medium">
                                            {{ $statusLabels[$supervisor['status']] ?? $supervisor['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center mb-1"><i class="bi bi-telephone mr-2 text-gray-400"></i>
                                            {{ $supervisor['contact_number'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openSupervisorHistoryMap('{{ $supervisor['id'] }}', '{{ $supervisor['superviser_name'] }}')"
                                                class="text-gray-500 hover:text-indigo-600 p-1" title="Track on Map">
                                                <i class="bi bi-geo-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                        No supervisors found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- No Results Msg (JS) -->
                <div id="noSupervisorsFound" class="hidden px-6 py-12 text-center text-gray-500">
                    <i class="bi bi-search text-4xl mb-3 block text-gray-300"></i>
                    No matching supervisors found
                </div>
            </div>
        </div>
    </div>

    <!-- Map Tracking Modal -->
    <div id="map-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div id="map-backdrop"
            class="fixed inset-0 bg-gray-900/75 transition-opacity opacity-0 transition-opacity duration-300 ease-out"
            onclick="closeMapModal()"></div>

        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div id="map-panel"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 transition-all duration-300 ease-out flex flex-col max-h-[90vh]">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="map-modal-title">Supervisor Locations</h3>
                        <p class="text-sm text-gray-500" id="map-modal-desc">Real-time tracking visualizer</p>
                    </div>
                    <!-- Date Selector -->
                    <div id="map-date-container" class="flex items-center gap-2">
                        <label for="map-history-date" class="text-xs font-medium text-gray-700">Select Date:</label>
                        <input type="date" id="map-history-date" 
                            class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                            value="{{ date('Y-m-d') }}" onchange="handleMapDateChange()">
                    </div>
                </div>

                <div class="p-4 flex-1">
                    <div id="supervisor-track-map" class="w-full h-[600px] rounded-lg bg-gray-100 border border-gray-200"></div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100 sticky bottom-0">
                    <div id="map-info-legend" class="text-xs text-gray-600 flex items-center gap-4">
                        <!-- Legend dynamically populated -->
                    </div>
                    <button type="button" onclick="closeMapModal()"
                        class="px-5 py-2 rounded-lg bg-white border border-gray-300 text-sm font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search & Filter Logic
        function filterSupervisors() {
            const search = document.getElementById('supervisorSearch').value.toLowerCase();
            const agentFilter = document.getElementById('agentFilter').value;
            const rows = document.querySelectorAll('.supervisor-row');

            let visibleCount = 0;

            rows.forEach(row => {
                const rowSearch = row.dataset.search;
                const rowAgent = row.dataset.agentId;

                const matchSearch = rowSearch.includes(search);
                const matchAgent = agentFilter === 'all' || rowAgent === agentFilter;

                if (matchSearch && matchAgent) {
                    row.style.display = 'table-row';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('supervisorCount').innerText = visibleCount;
            const noRes = document.getElementById('noSupervisorsFound');
            if (visibleCount === 0 && rows.length > 0) {
                noRes.classList.remove('hidden');
            } else {
                noRes.classList.add('hidden');
            }
        }

        document.getElementById('supervisorSearch').addEventListener('keyup', filterSupervisors);
        document.getElementById('agentFilter').addEventListener('change', filterSupervisors);

        // ==========================================
        // MAP TRACKING LOGIC
        // ==========================================
        const mapModal = document.getElementById('map-modal');
        const mapBackdrop = document.getElementById('map-backdrop');
        const mapPanel = document.getElementById('map-panel');
        const mapTitle = document.getElementById('map-modal-title');
        const mapDesc = document.getElementById('map-modal-desc');
        const mapHistoryDate = document.getElementById('map-history-date');
        const mapLegend = document.getElementById('map-info-legend');

        let trackMapObj = null;
        let trackMarkers = [];
        let trackPath = null;
        let activeSupervisorId = null;
        let mapMode = 'all'; // 'all' or 'single'

        let directionsService = null;
        let directionsRenderer = null;

        function initTrackMap() {
            if (trackMapObj) return;
            const mapDiv = document.getElementById('supervisor-track-map');
            const defaultCenter = { lat: 6.9271, lng: 79.8612 }; // Default Colombo center
            trackMapObj = new google.maps.Map(mapDiv, {
                center: defaultCenter,
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                map: trackMapObj,
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: '#6366F1',
                    strokeWeight: 4
                }
            });
        }

        function clearMap() {
            trackMarkers.forEach(marker => marker.setMap(null));
            trackMarkers = [];
            if (trackPath) {
                trackPath.setMap(null);
                trackPath = null;
            }
            if (directionsRenderer) {
                directionsRenderer.setDirections({routes: []});
            }
        }

        function openMapModal() {
            mapModal.classList.remove('hidden');
            setTimeout(() => {
                mapBackdrop.classList.remove('opacity-0');
                mapBackdrop.classList.add('opacity-100');
                mapPanel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                mapPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
                initTrackMap();
            }, 10);
        }

        function closeMapModal() {
            mapBackdrop.classList.remove('opacity-100');
            mapBackdrop.classList.add('opacity-0');
            mapPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            mapPanel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            setTimeout(() => {
                mapModal.classList.add('hidden');
                clearMap();
            }, 300);
        }

        function handleMapDateChange() {
            if (mapMode === 'all') {
                loadAllSupervisorsLocations();
            } else {
                loadSupervisorHistoryFromDate();
            }
        }

        function openAllSupervisorsMap() {
            mapMode = 'all';
            mapHistoryDate.value = new Date().toISOString().split('T')[0];
            openMapModal();
            loadAllSupervisorsLocations();
        }

        function loadAllSupervisorsLocations() {
            const dateVal = mapHistoryDate.value || new Date().toISOString().split('T')[0];
            const agentFilterVal = document.getElementById('agentFilter').value;
            
            mapTitle.innerText = "All Supervisors Locations";
            mapDesc.innerText = `Supervisor coordinates for date: ${dateVal}`;
            mapLegend.innerHTML = `
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded-full bg-indigo-500 flex items-center justify-center text-[10px] text-white font-bold">S</span>
                    <span>Active Supervisors</span>
                </div>
            `;
            
            // Build URL
            let url = `/api/supervisors/locations/all?date=${dateVal}`;
            if (agentFilterVal !== 'all') {
                url += `&agent_id=${agentFilterVal}`;
            }

            // Fetch locations
            fetch(url)
                .then(res => res.json())
                .then(res => {
                    clearMap();
                    if (!res.status || res.data.length === 0) {
                        mapLegend.innerHTML = `<span class="text-red-500 font-semibold">No supervisor locations for this date</span>`;
                        Swal.fire('No Data', 'No supervisor tracking data available for the selected date.', 'info');
                        return;
                    }

                    const bounds = new google.maps.LatLngBounds();
                    res.data.forEach(loc => {
                        const pos = { lat: loc.lat, lng: loc.long };
                        bounds.extend(pos);

                        const marker = new google.maps.Marker({
                            position: pos,
                            map: trackMapObj,
                            title: `${loc.superviser_name} (${loc.superviser_code})`,
                            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
                        });

                        const infoWindow = new google.maps.InfoWindow({
                            content: `
                                <div class="p-2">
                                    <h4 class="font-bold text-sm text-gray-900">${loc.superviser_name}</h4>
                                    <p class="text-xs text-gray-500 mb-1">Code: ${loc.superviser_code}</p>
                                    <p class="text-xs text-gray-600 mb-1">Phone: ${loc.phone || '-'}</p>
                                    <p class="text-xs text-gray-600 mb-1">Agent: ${loc.agent_name}</p>
                                    <p class="text-[10px] text-gray-400">Last updated: ${loc.date}</p>
                                </div>
                            `
                        });

                        marker.addListener('click', () => {
                            infoWindow.open(trackMapObj, marker);
                        });

                        trackMarkers.push(marker);
                    });

                    trackMapObj.fitBounds(bounds);
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Failed to fetch supervisor locations.', 'error');
                });
        }

        function openSupervisorHistoryMap(supervisorId, name) {
            mapMode = 'single';
            activeSupervisorId = supervisorId;
            mapTitle.innerText = `${name} Location History`;
            mapDesc.innerText = `Track coordinates history`;
            mapHistoryDate.value = new Date().toISOString().split('T')[0];
            
            openMapModal();
            loadSupervisorHistoryFromDate();
        }

        function loadSupervisorHistoryFromDate() {
            if (!activeSupervisorId) return;
            const dateVal = mapHistoryDate.value;
            if (!dateVal) return;

            fetch(`/api/supervisors/${activeSupervisorId}/locations/history?date=${dateVal}`)
                .then(res => res.json())
                .then(res => {
                    clearMap();
                    if (!res.status || res.data.history.length === 0) {
                        mapLegend.innerHTML = `<span class="text-red-500 font-semibold">No history data for this date</span>`;
                        Swal.fire('No Data', 'No location history found for the selected date.', 'info');
                        return;
                    }

                    const history = res.data.history;
                    mapLegend.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-blue-600 flex items-center justify-center text-[10px] text-white font-bold">S</span>
                            <span>Start (${history[0].date})</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3.5 h-3.5 rounded-full bg-red-600 flex items-center justify-center text-[10px] text-white font-bold">E</span>
                            <span>End (${history[history.length - 1].date})</span>
                        </div>
                        <div class="text-[11px] text-gray-500 font-medium">Points: ${history.length}</div>
                    `;

                    const pathCoords = [];
                    const bounds = new google.maps.LatLngBounds();

                    history.forEach((point, index) => {
                        const pos = { lat: point.lat, lng: point.long };
                        pathCoords.push(pos);
                        bounds.extend(pos);

                        const isStart = index === 0;
                        const isEnd = index === history.length - 1;

                        if (isStart || isEnd) {
                            const marker = new google.maps.Marker({
                                position: pos,
                                map: trackMapObj,
                                label: isStart ? 'S' : 'E',
                                title: `${isStart ? 'Start' : 'End'} point at ${point.date}`,
                                icon: isStart ? 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png' : 'http://maps.google.com/mapfiles/ms/icons/red-dot.png'
                            });

                            const infoWindow = new google.maps.InfoWindow({
                                content: `<div class="p-1 text-xs font-semibold">${isStart ? 'Start' : 'End'}: ${point.date}</div>`
                            });

                            marker.addListener('click', () => {
                                infoWindow.open(trackMapObj, marker);
                            });

                            trackMarkers.push(marker);
                        } else {
                            // Intermediate point - draw a small yellow circle
                            const marker = new google.maps.Marker({
                                position: pos,
                                map: trackMapObj,
                                title: `Time: ${point.date}`,
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 5,
                                    fillColor: '#F59E0B',
                                    fillOpacity: 1.0,
                                    strokeColor: '#D97706',
                                    strokeWeight: 1.5
                                }
                            });

                            const infoWindow = new google.maps.InfoWindow({
                                content: `<div class="p-1 text-xs font-semibold">Time: ${point.date}</div>`
                            });

                            marker.addListener('click', () => {
                                infoWindow.open(trackMapObj, marker);
                            });

                            trackMarkers.push(marker);
                        }
                    });

                    // Draw route: use DirectionsService if 2 or more points
                    if (history.length >= 2) {
                        const chunkSize = 20; // 1 origin, 18 waypoints, 1 destination
                        let chunkIndex = 0;

                        function requestNextChunk() {
                            if (chunkIndex >= history.length - 1) return;

                            const startIdx = chunkIndex;
                            const endIdx = Math.min(chunkIndex + chunkSize, history.length - 1);
                            
                            const chunk = history.slice(startIdx, endIdx + 1);
                            const chunkWaypoints = [];
                            for (let i = 1; i < chunk.length - 1; i++) {
                                chunkWaypoints.push({
                                    location: new google.maps.LatLng(chunk[i].lat, chunk[i].long),
                                    stopover: true
                                });
                            }

                            const request = {
                                origin: new google.maps.LatLng(chunk[0].lat, chunk[0].long),
                                destination: new google.maps.LatLng(chunk[chunk.length - 1].lat, chunk[chunk.length - 1].long),
                                waypoints: chunkWaypoints,
                                travelMode: google.maps.TravelMode.DRIVING
                            };

                            directionsService.route(request, function(result, status) {
                                if (status == google.maps.DirectionsStatus.OK) {
                                    const renderer = new google.maps.DirectionsRenderer({
                                        map: trackMapObj,
                                        suppressMarkers: true,
                                        preserveViewport: true,
                                        polylineOptions: {
                                            strokeColor: '#6366F1',
                                            strokeWeight: 4
                                        }
                                    });
                                    renderer.setDirections(result);
                                    trackMarkers.push(renderer);
                                } else {
                                    console.warn("Directions chunk failed due to " + status + ". Falling back to polyline.");
                                    const chunkCoords = chunk.map(p => ({ lat: p.lat, lng: p.long }));
                                    drawFallbackPolyline(chunkCoords);
                                }

                                // Move to next chunk
                                chunkIndex = endIdx;
                                requestNextChunk();
                            });
                        }

                        requestNextChunk();
                    } else {
                        drawFallbackPolyline(pathCoords);
                    }

                    function drawFallbackPolyline(coords) {
                        const lineSymbol = {
                            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                            scale: 2.5,
                            strokeColor: '#312E81'
                        };

                        const polyline = new google.maps.Polyline({
                            path: coords,
                            geodesic: true,
                            strokeColor: '#6366F1',
                            strokeOpacity: 1.0,
                            strokeWeight: 4,
                            icons: [{
                                icon: lineSymbol,
                                offset: '100%',
                                repeat: '80px'
                            }],
                            map: trackMapObj
                        });
                        trackMarkers.push(polyline);
                    }

                    trackMapObj.fitBounds(bounds);
                    if (history.length === 1) {
                        trackMapObj.setZoom(16);
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Failed to fetch location history.', 'error');
                });
        }
    </script>

    {{-- Google Maps API loaded from config passed --}}
    @if(isset($googleMapsKey))
        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&libraries=places,geometry&loading=async"></script>
    @endif
@endsection
