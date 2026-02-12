<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Route Builder - {{ data_get($route, 'route_name') }} | BakeryMate ERP</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/bakery.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2.min.css') }}">
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/sweetalert2.all.min.js') }}"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyD-Z0FCjm3sgq4hhNqgZfhiKjOWNuuXw&libraries=places,geometry&loading=async"></script>
</head>

<body class="h-full bg-gray-50">
    <div class="h-full flex flex-col">
        <!-- Header with Back Button -->
        <div class="bg-white border-b border-gray-200 p-4 sticky top-0 z-50 shadow-sm">
            <div class="max-w-full mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <!-- Title and Back Button -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('routeManagement.index') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="bi bi-arrow-left text-2xl"></i>
                    </a>
                    <div>
                        <h1 class="text-gray-900 text-xl font-bold">Route Builder</h1>
                        <p class="text-sm text-gray-600">{{ data_get($route, 'route_name') }} ({{ data_get($route, 'route_code') }})</p>
                    </div>
                </div>

                <div class="flex  justify-center gap-4">
                    <!-- Route Name and Agent Selection -->
                    <div class="flex items-center gap-4">
                        <input type="text" id="builder-route-name" placeholder="Route Name" value="{{ data_get($route, 'route_name') }}"
                            class="px-3 py-1.5 border border-gray-300 rounded text-sm w-64 focus:ring-amber-500 focus:border-amber-500">
                        <select id="builder-agent-select"
                            class="px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Select Agent (Optional)</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent['id'] }}" {{ data_get($route, 'agent_id') == $agent['id'] ? 'selected' : '' }}>
                                    {{ $agent['agentName'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
    
                    <!-- View Toggle -->
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button onclick="setBuilderView('list')" id="btn-view-list"
                            class="px-3 py-1.5 text-sm font-medium rounded-md shadow-sm bg-white text-gray-900 transition-all">
                            <i class="bi bi-list-ul mr-1"></i> List
                        </button>
                        <button onclick="setBuilderView('map')" id="btn-view-map"
                            class="px-3 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition-all">
                            <i class="bi bi-map mr-1"></i> Map
                        </button>
                    </div>
    
                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <button onclick="saveBuilderRoute()"
                            class="px-4 py-2 bg-[#D4A017] hover:bg-[#B8860B] text-white rounded-lg text-sm font-medium shadow-sm transition-colors flex items-center">
                            <i class="bi bi-save mr-2"></i> Save Route
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Bar -->
        <div class="bg-gray-50 border-b border-gray-200 p-4">
            <div class="max-w-full mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500 font-medium uppercase">Total Stops</span>
                        <i class="bi bi-geo-alt text-blue-600"></i>
                    </div>
                    <div class="text-xl font-bold text-gray-900" id="stat-stops">0</div>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500 font-medium uppercase">Distance</span>
                        <i class="bi bi-signpost-2 text-green-600"></i>
                    </div>
                    <div class="text-xl font-bold text-gray-900" id="stat-distance">0.00 km</div>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500 font-medium uppercase">Duration</span>
                        <i class="bi bi-clock text-orange-600"></i>
                    </div>
                    <div class="text-xl font-bold text-gray-900" id="stat-duration">0h 0m</div>
                </div>
                <div class="bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs text-gray-500 font-medium uppercase">Directions</span>
                        <i class="bi bi-map text-purple-600"></i>
                    </div>
                    <button onclick="openGoogleMaps()" id="btn-directions" disabled
                        class="w-full text-left text-sm text-blue-600 hover:text-blue-800 disabled:text-gray-400 font-medium truncate">
                        <i class="bi bi-box-arrow-up-right mr-1"></i> Open Maps
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-hidden relative">
            <!-- List View -->
            <div id="view-list" class="h-full flex overflow-hidden">
                <!-- Left Sidebar: Available Customers -->
                <div class="w-80 border-r border-gray-200 bg-gray-50 flex flex-col">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <h3 class="font-medium text-gray-900 mb-2 flex items-center gap-2">
                            <i class="bi bi-people text-gray-500"></i> Available Customers
                        </h3>
                        <input type="text" id="customer-search" placeholder="Search customers..."
                            class="w-full px-3 py-1.5 border border-gray-300 rounded text-sm focus:ring-amber-500 focus:border-amber-500">
                        <div class="text-xs text-gray-500 mt-2" id="available-count">0 unassigned</div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-3 space-y-2" id="available-list">
                        <!-- Available Customers Items -->
                    </div>
                </div>

                <!-- Right Panel: Route Stops -->
                <div class="flex-1 bg-white flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-gray-50/50">
                        <h3 class="font-medium text-gray-900 flex items-center gap-2">
                            <i class="bi bi-truck text-[#D4A017]"></i> Route Stops Sequence
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Drag customers here to add. Drag stops to reorder.</p>
                    </div>
                    <div class="flex-1 overflow-y-auto p-6" id="route-stops-container" ondragover="allowDrop(event)"
                        ondrop="dropOnRoute(event)">
                        <!-- Route Stops Items -->
                        <div id="empty-route-msg"
                            class="h-full flex flex-col items-center justify-center text-center opacity-60">
                            <i class="bi bi-sign-turn-right text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900">Start Building Your Route</h3>
                            <p class="text-gray-500 max-w-sm">Drag customers from the list on the left and drop them here.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map View -->
            <div id="view-map" class="h-full hidden relative bg-gray-100">
                <div id="route-map" class="w-full h-full"></div>
            </div>
        </div>
    </div>

<!-- Template for Available Customer Item -->
<template id="tpl-available-customer">
    <div class="bg-white p-3 rounded border border-gray-200 shadow-sm cursor-grab hover:border-amber-400 hover:shadow-md transition-all draggable-customer"
        draggable="true" ondragstart="dragStartCustomer(event)">
        <div class="flex items-start gap-2">
            <i class="bi bi-grip-vertical text-gray-400 mt-1"></i>
            <div class="min-w-0">
                <h4 class="text-sm font-medium text-gray-900 truncate business-name"></h4>
                <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                    <i class="bi bi-geo-alt"></i> <span class="truncate city-name"></span>
                </div>
                <span
                    class="badge-type inline-block mt-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 text-[10px] rounded border border-blue-100 uppercase tracking-wide"></span>
            </div>
        </div>
    </div>
</template>

<!-- Template for Route Stop Item -->
<template id="tpl-route-stop">
    <div class="route-stop-wrapper mb-2">
        <!-- Connector -->
        <div class="connector hidden ml-6 pl-0.5 border-l-2 border-dashed border-gray-300 h-8 flex items-center mb-2">
            <div
                class="ml-4 text-xs text-gray-500 flex gap-3 bg-gray-50 px-2 py-0.5 rounded-full border border-gray-200">
                <span class="flex items-center gap-1"><i class="bi bi-arrows-expand"></i> <span class="dist-val">0
                        km</span></span>
                <span class="flex items-center gap-1"><i class="bi bi-stopwatch"></i> <span class="dur-val">0
                        min</span></span>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-white border-2 border-gray-200 rounded-lg p-3 flex items-start gap-3 hover:border-amber-400 shadow-sm transition-all cursor-move"
            draggable="true" ondragstart="dragStartStop(event)" ondragover="allowDrop(event)"
            ondrop="dropOnStop(event)">
            <div
                class="flex-shrink-0 w-8 h-8 rounded-full bg-[#D4A017] text-white flex items-center justify-center font-bold text-sm shadow-sm seq-num">
                1</div>
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 business-name"></h4>
                        <p class="text-sm text-gray-500 address-text flex items-center gap-1 mt-0.5">
                            <i class="bi bi-geo-alt text-gray-400"></i> <span></span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Green checkmark for saved customers -->
                        <span class="saved-indicator hidden bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-medium border border-green-200">
                            <i class="bi bi-check-circle-fill"></i> Saved
                        </span>
                        <button class="text-gray-400 hover:text-red-600 transition-colors p-1" onclick="removeStop(this)"
                            title="Remove Stop">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-2 flex gap-4 text-xs text-gray-500 border-t border-gray-100 pt-2">
                    <span class="contact-person"><i class="bi bi-person mr-1"></i> <span></span></span>
                    <span class="phone-number"><i class="bi bi-telephone mr-1"></i> <span></span></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // State
    let customers = @json($customers);
    let agents = @json($agents);
    let currentRouteId = {{ $route->id }};
    let builderStops = [];
    let draggedItem = null;
    let draggedType = null; // 'customer' or 'stop'
    let map = null;
    let mapMarkers = [];
    let mapPolyline = null;

    function openVisualBuilder(routeJson = null) {
        const modal = document.getElementById('visual-route-builder');
        const title = document.getElementById('builder-title');
        const nameInput = document.getElementById('builder-route-name');
        const agentSelect = document.getElementById('builder-agent-select');

        modal.classList.remove('hidden');
        builderStops = [];

        if (routeJson) {
            const route = JSON.parse(routeJson);
            currentRouteId = route.id;
            title.innerText = 'Edit Route: ' + route.routeName;
            nameInput.value = route.routeName;
            
            // Auto-select agent and make readonly
            agentSelect.value = route.agentId || '';
            agentSelect.disabled = true;
            
            // Load existing stops for this route
            builderStops = customers
                .filter(c => c.assignedRouteId === route.id)
                .sort((a, b) => (a.stopSequence || 999) - (b.stopSequence || 999));

        } else {
            currentRouteId = null;
            title.innerText = 'Build New Route';
            nameInput.value = '';
            agentSelect.value = '';
            agentSelect.disabled = false;
        }

        renderBuilder();
    }

    function closeVisualBuilder() {
        document.getElementById('visual-route-builder').classList.add('hidden');
    }

    async function setBuilderView(mode) {
        document.getElementById('view-list').classList.toggle('hidden', mode !== 'list');
        document.getElementById('view-map').classList.toggle('hidden', mode !== 'map');

        const btnList = document.getElementById('btn-view-list');
        const btnMap = document.getElementById('btn-view-map');

        if (mode === 'list') {
            btnList.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            btnList.classList.remove('text-gray-500');
            btnMap.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
            btnMap.classList.add('text-gray-500');
        } else {
            btnMap.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
            btnMap.classList.remove('text-gray-500');
            btnList.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
            btnList.classList.add('text-gray-500');
            
            // Initialize or update map
            if (!map) {
                try {
                    await initMap();
                } catch (error) {
                    console.error("Map initialization failed:", error);
                    const mapDiv = document.getElementById('route-map');
                    if (mapDiv) {
                        mapDiv.innerHTML = `
                            <div class="flex flex-col items-center justify-center h-full text-gray-500">
                                <i class="bi bi-exclamation-triangle text-4xl mb-2 text-amber-500"></i>
                                <p class="font-medium">Map could not be loaded.</p>
                                <p class="text-xs mt-1">Please check your internet connection or disable ad-blockers.</p>
                            </div>
                        `;
                    }
                    return;
                }
            }
            
            // Wait a bit for the map container to be visible
            setTimeout(() => {
                updateMapMarkers();
            }, 100);
        }
    }

    async function initMap() {
        // Wait for Google Maps API to be ready
        if (typeof google === 'undefined' || !google.maps) {
            return new Promise((resolve, reject) => {
                const checkInterval = setInterval(() => {
                    if (typeof google !== 'undefined' && google.maps) {
                        clearInterval(checkInterval);
                        initializeMap().then(resolve).catch(reject);
                    }
                }, 100);
                
                setTimeout(() => {
                    clearInterval(checkInterval);
                    reject(new Error('Google Maps failed to load'));
                }, 10000);
            });
        } else {
            return initializeMap();
        }
    }

    async function initializeMap() {
        const mapDiv = document.getElementById('route-map');
        if (!mapDiv) throw new Error('Map container not found');

        const defaultCenter = { lat: 6.9271, lng: 79.8612 };
        
        map = new google.maps.Map(mapDiv, {
            center: defaultCenter,
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        
        console.log('Map initialized successfully');
    }

    function updateMapMarkers() {
        if (!map) return;
        
        // Clear existing markers
        mapMarkers.forEach(m => m.setMap(null));
        mapMarkers = [];
        
        if (mapPolyline) {
            mapPolyline.setMap(null);
        }

        if (builderStops.length === 0) return;

        const bounds = new google.maps.LatLngBounds();
        const pathCoordinates = [];

        builderStops.forEach((stop, index) => {
            const position = { 
                lat: parseFloat(stop.location.latitude), 
                lng: parseFloat(stop.location.longitude) 
            };
            
            pathCoordinates.push(position);
            bounds.extend(position);

            // Create standard marker with label
            const marker = new google.maps.Marker({
                position: position,
                map: map,
                label: {
                    text: (index + 1).toString(),
                    color: 'white',
                    fontWeight: 'bold'
                },
                title: stop.businessName,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 20,
                    fillColor: '#D4A017',
                    fillOpacity: 1,
                    strokeColor: '#b8860b',
                    strokeWeight: 2
                }
            });
            
            // InfoWindow with location details
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div class="p-2" style="min-width: 200px;">
                        <strong class="text-gray-900">${stop.businessName}</strong>
                        <div class="text-xs text-gray-600 mt-1">
                            ${stop.location.address}
                        </div>
                        <div class="text-xs text-gray-500 mt-2 border-t pt-1">
                            <div>Lat: ${position.lat.toFixed(6)}</div>
                            <div>Lng: ${position.lng.toFixed(6)}</div>
                        </div>
                    </div>
                `
            });
            
            // Show on hover
            marker.addListener('mouseover', () => {
                infoWindow.open(map, marker);
            });
            
            // Hide on mouseout
            marker.addListener('mouseout', () => {
                infoWindow.close();
            });
            
            // Also show on click (for mobile)
            marker.addListener('click', () => {
                infoWindow.open(map, marker);
            });

            mapMarkers.push(marker);
        });

        // Draw Polyline
        mapPolyline = new google.maps.Polyline({
            path: pathCoordinates,
            geodesic: true,
            strokeColor: '#D4A017',
            strokeOpacity: 1.0,
            strokeWeight: 4,
            icons: [{
                icon: {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                },
                offset: '100%',
                repeat: '50px'
            }]
        });

        mapPolyline.setMap(map);

        // Fit bounds
        map.fitBounds(bounds);
        
        // Zoom out listener
        const listener = google.maps.event.addListenerOnce(map, "idle", function() { 
            if (map.getZoom() > 16) map.setZoom(16); 
        });
    }

    // Rendering
    function renderBuilder() {
        renderAvailableList();
        renderRouteStops();
        updateStats();
        
        if (!document.getElementById('view-map').classList.contains('hidden')) {
            updateMapMarkers();
        }
    }

    function renderAvailableList() {
        console.log('=== renderAvailableList START ===');
        const container = document.getElementById('available-list');
        const search = document.getElementById('customer-search').value.toLowerCase();
        const countBadge = document.getElementById('available-count');

        if (!container) {
            console.error('CONTAINER NOT FOUND: available-list');
            return;
        }

        console.log('Total customers:', customers.length);
        console.log('Current builderStops:', builderStops.length);

        // Clear ALL content from container
        container.innerHTML = '';

        // Filter: Not in builderStops AND matches search
        const available = customers.filter(c => {
            const notInStops = !builderStops.find(s => s.id === c.id);
            const matchesSearch = c.businessName.toLowerCase().includes(search) || c.location.city.toLowerCase().includes(search);
            return notInStops && matchesSearch;
        });

        console.log('Available customers after filter:', available.length);

        if (available.length === 0) {
            // Show empty message
            container.innerHTML = `
                <div class="text-center py-8 text-gray-400">
                    <i class="bi bi-search text-3xl mb-2 block"></i>
                    <p class="text-sm">No customers available</p>
                </div>
            `;
            countBadge.innerText = '0 unassigned';
        } else {
            // Create customer cards directly
            available.forEach((c, index) => {
                console.log(`Creating card ${index + 1}: ${c.businessName}`);
                
                const card = document.createElement('div');
                card.className = 'bg-white p-3 rounded border border-gray-200 shadow-sm cursor-grab hover:border-amber-400 hover:shadow-md transition-all draggable-customer mb-2';
                card.draggable = true;
                card.dataset.id = c.id;
                card.ondragstart = dragStartCustomer;
                
                card.innerHTML = `
                    <div class="flex items-start gap-2">
                        <i class="bi bi-grip-vertical text-gray-400 mt-1"></i>
                        <div class="min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">${c.businessName}</h4>
                            <div class="text-xs text-gray-500 flex items-center gap-1 mt-0.5">
                                <i class="bi bi-geo-alt"></i> <span class="truncate">${c.location.city}</span>
                            </div>
                            <span class="inline-block mt-1 px-1.5 py-0.5 bg-blue-50 text-blue-700 text-[10px] rounded border border-blue-100 uppercase tracking-wide">
                                ${c.b2bType.replace('_', ' ')}
                            </span>
                        </div>
                    </div>
                `;
                
                container.appendChild(card);
            });
            
            countBadge.innerText = available.length + ' unassigned';
            console.log('Successfully rendered', available.length, 'customers');
        }
        
        console.log('=== renderAvailableList END ===');
    }

    function renderRouteStops() {
        const container = document.getElementById('route-stops-container');
        const tpl = document.getElementById('tpl-route-stop');
        const emptyMsg = document.getElementById('empty-route-msg');

        // Clear except empty msg (which we toggle)
        container.querySelectorAll('.route-stop-wrapper').forEach(e => e.remove());

        if (builderStops.length === 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');

            builderStops.forEach((stop, index) => {
                const clone = tpl.content.cloneNode(true);
                const wrapper = clone.querySelector('.route-stop-wrapper');

                // Determine if we can use saved data
                // Condition: 
                // 1. Has saved data
                // 2. Is adjacent to the previous stop as per original DB sequence
                let useSavedData = false;
                
                if (stop.savedDistance !== null && stop.savedDistance !== undefined) {
                    if (index === 0) {
                        // First stop (Start) effectively has 0 distance, usually handled but strict check:
                        // If it was seq 1, it's valid.
                        if (stop.stopSequence === 1) useSavedData = true;
                    } else {
                        const prevStop = builderStops[index - 1];
                        // Check strict adjacency: current stop was originally immediately after the previous stop
                        if (stop.stopSequence && prevStop.stopSequence && 
                            stop.stopSequence === prevStop.stopSequence + 1) {
                            useSavedData = true;
                        }
                    }
                }

                // Show green checkmark if saved (and valid)
                if (useSavedData) {
                    clone.querySelector('.saved-indicator').classList.remove('hidden');
                }

                // Connector logic (show if not first)
                if (index > 0) {
                    const conn = clone.querySelector('.connector');
                    conn.classList.remove('hidden');

                    if (useSavedData) {
                        // Use saved values
                        clone.querySelector('.dist-val').innerText = parseFloat(stop.savedDistance).toFixed(1) + ' km';
                        clone.querySelector('.dur-val').innerText = Math.round(stop.savedDuration) + ' min';
                    } else {
                        // Set loading state
                        clone.querySelector('.dist-val').innerText = 'Calculating...';
                        clone.querySelector('.dur-val').innerText = '...';
                    }
                }

                const card = clone.querySelector('.bg-white');
                card.dataset.index = index;

                clone.querySelector('.seq-num').innerText = index + 1;
                clone.querySelector('.business-name').innerText = stop.businessName;
                clone.querySelector('.address-text span').innerText = stop.location.address;
                clone.querySelector('.contact-person span').innerText = stop.contact.contactPerson;
                clone.querySelector('.phone-number span').innerText = stop.contact.phoneNumber;

                container.appendChild(clone);
                
                // Calculate distance AFTER appending to DOM (only if NOT using saved data)
                if (index > 0 && !useSavedData) {
                    const prev = builderStops[index - 1];
                    calculateRealDistance(prev, stop, index);
                }
            });
        }
    }

    // Calculate real distance and duration using Google Routes API
    async function calculateRealDistance(origin, destination, stopIndex) {
        console.log('=== calculateRealDistance START ===');
        console.log('Origin:', origin.businessName, origin.location);
        console.log('Destination:', destination.businessName, destination.location);
        console.log('Stop Index:', stopIndex);
        
        const container = document.getElementById('route-stops-container');
        const stopCards = container.querySelectorAll('.route-stop-wrapper');
        
        if (stopIndex >= stopCards.length) {
            console.error('Invalid stop index:', stopIndex);
            return;
        }
        
        const stopElement = stopCards[stopIndex];
        const distElement = stopElement.querySelector('.dist-val');
        const durElement = stopElement.querySelector('.dur-val');
        
        if (!distElement || !durElement) {
            console.error('Could not find distance/duration elements');
            return;
        }
        
        // Use Google Routes API (newer, more accurate)
        const apiKey = 'AIzaSyDyD-Z0FCjm3sgq4hhNqgZfhiKjOWNuuXw';
        const url = 'https://routes.googleapis.com/directions/v2:computeRoutes';
        
        const requestBody = {
            origin: {
                location: {
                    latLng: {
                        latitude: origin.location.latitude,
                        longitude: origin.location.longitude
                    }
                }
            },
            destination: {
                location: {
                    latLng: {
                        latitude: destination.location.latitude,
                        longitude: destination.location.longitude
                    }
                }
            },
            travelMode: 'DRIVE',
            routingPreference: 'TRAFFIC_AWARE',
            computeAlternativeRoutes: false,
            routeModifiers: {
                avoidTolls: false,
                avoidHighways: false,
                avoidFerries: false
            },
            languageCode: 'en-US',
            units: 'METRIC'
        };

        console.log('Calling Routes API...');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Goog-Api-Key': apiKey,
                    'X-Goog-FieldMask': 'routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline'
                },
                body: JSON.stringify(requestBody)
            });

            const data = await response.json();
            console.log('Routes API Response:', data);

            if (response.ok && data.routes && data.routes.length > 0) {
                const route = data.routes[0];
                const distanceKm = (route.distanceMeters / 1000).toFixed(1);
                const durationSec = parseInt(route.duration.replace('s', ''));
                const durationMin = Math.round(durationSec / 60);

                console.log('✅ SUCCESS - Distance:', distanceKm, 'km, Duration:', durationMin, 'min');

                if (distElement && durElement) {
                    distElement.innerText = distanceKm + ' km';
                    durElement.innerText = durationMin + ' min';
                    
                    // Update total stats after API returns
                    updateStats();
                }
            } else {
                console.error('❌ Routes API FAILED:', data);
                // Fallback to Haversine
                const dist = calculateDistance(origin.location.latitude, origin.location.longitude,
                    destination.location.latitude, destination.location.longitude);
                const dur = Math.round((dist / 30) * 60);
                if (distElement && durElement) {
                    distElement.innerText = dist.toFixed(1) + ' km*';
                    durElement.innerText = dur + ' min*';
                    
                    // Update total stats after fallback
                    updateStats();
                }
            }
        } catch (error) {
            console.error('❌ Routes API Error:', error);
            // Fallback to Haversine
            const dist = calculateDistance(origin.location.latitude, origin.location.longitude,
                destination.location.latitude, destination.location.longitude);
            const dur = Math.round((dist / 30) * 60);
            if (distElement && durElement) {
                distElement.innerText = dist.toFixed(1) + ' km*';
                durElement.innerText = dur + ' min*';
                
                // Update total stats after fallback
                updateStats();
            }
        }
    }

    // Drag and Drop Logic
    document.getElementById('customer-search').addEventListener('keyup', renderAvailableList);

    function dragStartCustomer(e) {
        draggedType = 'customer';
        draggedItem = e.target.dataset.id;
        e.dataTransfer.effectAllowed = 'copy';
    }

    function dragStartStop(e) {
        draggedType = 'stop';
        draggedItem = parseInt(e.target.dataset.index); // Store index
        e.dataTransfer.effectAllowed = 'move';

        // Add minimal delay to allow drag image generation
        setTimeout(() => e.target.classList.add('opacity-50'), 0);
    }

    function allowDrop(e) {
        e.preventDefault();
    }

    function dropOnRoute(e) {
        e.preventDefault();
        if (draggedType === 'customer') {
            const customerId = parseInt(draggedItem);
            const customer = customers.find(c => c.id === customerId);
            if (customer) {
                builderStops.push(customer);
                renderBuilder();
            }
        }
    }

    function dropOnStop(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent bubbling to container drop

        const targetCard = e.target.closest('.route-stop-wrapper').querySelector('.bg-white');
        const targetIndex = parseInt(targetCard.dataset.index);

        if (draggedType === 'stop' && draggedItem !== null) {
            const fromIndex = draggedItem;
            // Move item in array
            const item = builderStops.splice(fromIndex, 1)[0];
            builderStops.splice(targetIndex, 0, item);
            renderBuilder();
        } else if (draggedType === 'customer') {
            const customerId = parseInt(draggedItem);
            const customer = customers.find(c => c.id === customerId);
            if (customer) {
                builderStops.splice(targetIndex, 0, customer);
                renderBuilder();
            }
        }
    }

    function removeStop(btn) {
        const index = parseInt(btn.closest('.bg-white').dataset.index);
        builderStops.splice(index, 1);
        renderBuilder();
    }

    // Stats & Utils
    function updateStats() {
        document.getElementById('stat-stops').innerText = builderStops.length;

        // Sum up the REAL distances and durations from the displayed route segments
        let totalDist = 0;
        let totalDur = 0;

        const container = document.getElementById('route-stops-container');
        const stopCards = container.querySelectorAll('.route-stop-wrapper');
        
        stopCards.forEach((card, index) => {
            if (index > 0) {  // Skip first stop (no distance before it)
                const distElement = card.querySelector('.dist-val');
                const durElement = card.querySelector('.dur-val');
                
                if (distElement && durElement) {
                    const distText = distElement.innerText || '0 km';
                    const durText = durElement.innerText || '0 min';
                    
                    // Parse distance (handles '5.2 km', '5.2 km*', 'Calculating...', etc)
                    const distMatch = distText.match(/([\d.]+)/);
                    if (distMatch && distText !== 'Calculating...') {
                        totalDist += parseFloat(distMatch[1]);
                    }
                    
                    // Parse duration (handles '15 min', '15 min*', '...', etc)
                    const durMatch = durText.match(/(\d+)/);
                    if (durMatch && durText !== '...') {
                        totalDur += parseInt(durMatch[1]);
                    }
                }
            }
        });

        document.getElementById('stat-distance').innerText = totalDist.toFixed(2) + ' km';

        const hrs = Math.floor(totalDur / 60);
        const mins = Math.round(totalDur % 60);
        document.getElementById('stat-duration').innerText = `${hrs}h ${mins}m`;

        // Enable/Disable Google Maps button
        const btnDirections = document.getElementById('btn-directions');
        btnDirections.disabled = builderStops.length < 2;
        
        // Store totals for saving route
        window.builderTotalDistance = totalDist;
        window.builderTotalDuration = totalDur;
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth radius km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function openGoogleMaps() {
        if (builderStops.length < 2) return;

        const origin = `${builderStops[0].location.latitude},${builderStops[0].location.longitude}`;
        const dest = `${builderStops[builderStops.length - 1].location.latitude},${builderStops[builderStops.length - 1].location.longitude}`;

        let waypoints = '';
        if (builderStops.length > 2) {
            waypoints = builderStops.slice(1, -1).map(s => `${s.location.latitude},${s.location.longitude}`).join('|');
        }

        let url = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${dest}&travelmode=driving`;
        if (waypoints) url += `&waypoints=${waypoints}`;

        window.open(url, '_blank');
    }

    function saveBuilderRoute() {
        const name = document.getElementById('builder-route-name').value;
        const agentId = document.getElementById('builder-agent-select').value;
        
        if (!name) {
            alert('Please enter a route name');
            return;
        }
        
        if (!agentId) {
            alert('Please select an agent');
            return;
        }
        
        if (builderStops.length === 0) {
            alert('Please add at least one customer to the route');
            return;
        }

        // Collect distance and duration for each stop
        const container = document.getElementById('route-stops-container');
        const stopCards = container.querySelectorAll('.route-stop-wrapper');
        
        const stopsData = builderStops.map((stop, index) => {
            let distanceKm = null;
            let durationMinutes = null;
            
            if (index > 0) {
                const card = stopCards[index];
                const distText = card.querySelector('.dist-val')?.innerText || '';
                const durText = card.querySelector('.dur-val')?.innerText || '';
                
                const distMatch = distText.match(/([\d.]+)/);
                if (distMatch) distanceKm = parseFloat(distMatch[1]);
                
                const durMatch = durText.match(/(\d+)/);
                if (durMatch) durationMinutes = parseInt(durMatch[1]);
            } else {
                // First stop has 0 distance/duration from start
                distanceKm = 0;
                durationMinutes = 0;
            }
            
            return {
                customer_id: stop.id,
                stop_sequence: index + 1,
                distance_km: distanceKm,
                duration_minutes: durationMinutes
            };
        });

        const payload = {
            route_id: currentRouteId,
            route_name: name,
            agent_id: agentId,
            stops: stopsData,
            total_distance_km: window.builderTotalDistance || 0,
            total_duration_minutes: window.builderTotalDuration || 0
        };

        console.log('Saving route:', payload);

        fetch('/api/routes/save-builder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Route Saved',
                    text: `Route "${name}" saved with ${builderStops.length} stops.`,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    closeVisualBuilder();
                    location.reload(); // Reload to show updated route
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to save route'
                });
            }
        })
        .catch(error => {
            console.error('Error saving route:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving the route'
            });
        });
    }
</script>

<style>
    /* Draggable Styling */
    .draggable-customer:active {
        cursor: grabbing;
    }

    .route-stop-wrapper.dragging {
        opacity: 0.5;
    }

    /* Scrollbar */
    #available-list::-webkit-scrollbar,
    #route-stops-container::-webkit-scrollbar {
        width: 6px;
    }

    #available-list::-webkit-scrollbar-thumb,
    #route-stops-container::-webkit-scrollbar-thumb {
        background-color: #ddd;
        border-radius: 3px;
    }

    /* Map container uses flex layout from parent */
    #view-map {
        /* Height is managed by flex-1 parent */
    }
    
    #route-map {
        /* Height is managed by parent h-full */
    }
</style>
<!-- Google Maps API Script -->
<script>
    // Auto-populate and load existing stops
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page loaded, initializing route builder...');
        
        // Load existing stops for this route
        builderStops = customers
            .filter(c => c.assignedRouteId === currentRouteId)
            .sort((a, b) => (a.stopSequence || 999) - (b.stopSequence || 999));
        
        console.log('Loaded stops for route:', builderStops.length);
        
        renderBuilder();
    });
</script>

</body>
</html>
