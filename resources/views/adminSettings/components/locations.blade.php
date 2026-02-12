{{--
resources/views/settings/locations.blade.php

Expected variables:
$locations - Collection of Location models
--}}

{{-- Data loaded via AJAX --}}

<div class="space-y-6">

    {{-- Header with Tabs --}}
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- MapPin Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>

            <nav class="flex space-x-4" aria-label="Tabs">
                <button onclick="switchLocationTab('locations-tab', this)"
                    class="location-tab-btn px-3 py-2 font-medium text-sm rounded-md bg-[#D4A017]/10 text-[#D4A017]"
                    aria-current="page">
                    Locations & Branches
                </button>
                <button onclick="switchLocationTab('departments-tab', this)"
                    class="location-tab-btn px-3 py-2 font-medium text-sm rounded-md text-gray-500 hover:text-gray-700">
                    Departments
                </button>
            </nav>
        </div>

        <div id="location-actions">
            <button onclick="toggleAddLocationForm(true)"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Location
            </button>
        </div>

        <div id="department-actions" class="hidden">
            <button onclick="toggleAddDepartmentForm(true)"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Department
            </button>
        </div>
    </div>

    {{-- LOCATIONS TAB CONTENT --}}
    <div id="locations-tab" class="location-tab-content space-y-6">
        {{-- Add Location Form (Hidden by default) --}}
        <div id="add-location-form" class="hidden bg-white p-6 rounded-lg border-2 border-[#D4A017] shadow-sm">
            <form id="createLocationForm">
                @csrf
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Location</h3>
                    <button type="button" onclick="toggleAddLocationForm(false)"
                        class="text-gray-400 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    {{-- Basic Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Location Code
                                *</label>
                            <input type="text" name="code" id="code" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                placeholder="BR-XXX01">
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Location Name
                                *</label>
                            <input type="text" name="name" id="name" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                placeholder="Kandy Branch">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                                <button type="button" onclick="toggleBranchTypeModal(true)"
                                    class="text-[#D4A017] hover:text-[#B8860B] text-sm font-medium focus:outline-none">
                                    + Add New
                                </button>
                            </div>
                            <select name="type_id" id="type"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                                <option value="">Select Type</option>
                            </select>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Address</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="street" class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                                <input type="text" name="address[street]" id="street"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" name="address[city]" id="city"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            </div>
                            <div>
                                <label for="province"
                                    class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                <input type="text" name="address[province]" id="province"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            </div>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Contact</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact
                                    Person</label>
                                <input type="text" name="contact[person]" id="contact_person"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            </div>
                            <div>
                                <label for="contact_phone"
                                    class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="contact[phone]" id="contact_phone"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                            </div>
                        </div>
                    </div>

                    {{-- GL Accounts --}}
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">GL Accounts</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="gl_cash" class="block text-sm font-medium text-gray-700 mb-1">Cash
                                    Account</label>
                                <input type="text" name="gl_accounts[cash_account]" id="gl_cash"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                    placeholder="1010">
                            </div>
                            <div>
                                <label for="gl_inventory" class="block text-sm font-medium text-gray-700 mb-1">Inventory
                                    Account</label>
                                <input type="text" name="gl_accounts[inventory_account]" id="gl_inventory"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                    placeholder="1310">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button type="button" onclick="toggleAddLocationForm(false)"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        Add Location
                    </button>
                </div>
            </form>
        </div>

        {{-- Locations List --}}
        <div id="locations-list" class="grid gap-4">
            <div class="text-center py-8 text-gray-500">Loading locations...</div>
        </div>
    </div>

    {{-- DEPARTMENTS TAB CONTENT --}}
    <div id="departments-tab" class="location-tab-content hidden space-y-6">
        {{-- Add Department Form (Inline) --}}
        <div id="add-department-form" class="hidden bg-white p-6 rounded-lg border-2 border-[#D4A017] shadow-sm">
            <form id="createDepartmentForm">
                @csrf
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Department</h3>
                    <button type="button" onclick="toggleAddDepartmentForm(false)"
                        class="text-gray-400 hover:text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department Name *</label>
                        <input type="text" name="name" required placeholder="e.g., Pastry Station"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Theme</label>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'pink', 'indigo', 'gray'];
                            @endphp
                            @foreach($colors as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $color }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div
                                        class="w-8 h-8 rounded-full bg-{{ $color }}-500 peer-checked:ring-2 peer-checked:ring-offset-2 peer-checked:ring-{{ $color }}-600 hover:opacity-80 transition-opacity">
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            @php
                                $icons = [
                                    'box' => 'bi-box',
                                    'fire' => 'bi-fire',
                                    'cake' => 'bi-cake',
                                    'brush' => 'bi-brush',
                                    'tools' => 'bi-tools',
                                    'people' => 'bi-people',
                                    'shop' => 'bi-shop',
                                    'truck' => 'bi-truck'
                                ];
                            @endphp
                            @foreach($icons as $value => $iconClass)
                                <label class="cursor-pointer">
                                    <input type="radio" name="icon" value="{{ $value }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                    <div
                                        class="flex flex-col items-center justify-center p-2 border rounded-md peer-checked:border-[#D4A017] peer-checked:bg-amber-50 hover:bg-gray-50 transition-colors">
                                        <i
                                            class="bi {{ $iconClass }} text-xl mb-1 text-gray-600 peer-checked:text-[#D4A017]"></i>
                                        <span class="text-[10px] text-gray-500 capitalize">{{ $value }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                    <button type="button" onclick="toggleAddDepartmentForm(false)"
                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B]">
                        Create Department
                    </button>
                </div>
            </form>
        </div>

        {{-- Departments List --}}
        <div id="locations-departments-list" class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <div class="text-center py-8 text-gray-500 col-span-full">Loading departments...</div>
        </div>
    </div>

    {{-- Add Branch Type Modal --}}
    <div id="branch-type-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            {{--
            FIX 1: Removed 'backdrop-blur-sm'
            This removes the foggy/blurry effect from the background.
            --}}
            <div class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"
                onclick="toggleBranchTypeModal(false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{--
            FIX 2: Removed 'transform' and 'transition-all'
            Removing 'transform' prevents the text inside the modal from becoming blurry due to sub-pixel rendering.
            --}}
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                <form id="createBranchTypeForm">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                Add New Branch Type
                            </h3>
                            <div class="mb-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <label for="type_name" class="block text-sm font-medium text-gray-700 mb-1">Type
                                    Name *</label>
                                <input type="text" name="name" id="type_name" required
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                    placeholder="e.g. Virtual Kitchen">
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <label for="type_icon" class="block text-sm font-medium text-gray-700 mb-1">Icon
                                    (Bootstrap Class)</label>
                                <input type="text" name="icon" id="type_icon"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                                    placeholder="bi bi-shop">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button type="button" onclick="toggleBranchTypeModal(false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Assign Departments Modal --}}
    <div id="assign-department-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/75 transition-opacity" aria-hidden="true"
                onclick="toggleAssignDepartmentModal(false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
                <form id="assignDepartmentForm">
                    @csrf
                    <input type="hidden" name="branch_id" id="assign_branch_id">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Assign Departments to <span id="assign_branch_name" class="font-bold text-[#D4A017]"></span>
                        </h3>
                        <div class="space-y-4 max-h-60 overflow-y-auto" id="assign_departments_list">
                            {{-- Checkboxes will be injected here --}}
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] sm:ml-3 sm:w-auto sm:text-sm">
                            Save Assignments
                        </button>
                        <button type="button" onclick="toggleAssignDepartmentModal(false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // --- TABS LOGIC ---
    function switchLocationTab(tabId, btn) {
        // Hide all tabs
        document.querySelectorAll('.location-tab-content').forEach(el => el.classList.add('hidden'));

        // Show selected tab
        document.getElementById(tabId).classList.remove('hidden');

        // Reset buttons
        document.querySelectorAll('.location-tab-btn').forEach(b => {
            b.classList.remove('bg-[#D4A017]/10', 'text-[#D4A017]');
            b.classList.add('text-gray-500', 'hover:text-gray-700');
        });

        // Set active button
        btn.classList.remove('text-gray-500', 'hover:text-gray-700');
        btn.classList.add('bg-[#D4A017]/10', 'text-[#D4A017]');

        // Toggle Actions
        const locationActions = document.getElementById('location-actions');
        const departmentActions = document.getElementById('department-actions');

        if (tabId === 'locations-tab') {
            locationActions.classList.remove('hidden');
            departmentActions.classList.add('hidden');
        } else {
            locationActions.classList.add('hidden');
            departmentActions.classList.remove('hidden');
            fetchDepartments(); // Fetch on tab switch
        }
    }

    // --- DEPARTMENTS LOGIC ---
    function toggleAddDepartmentForm(show) {
        const form = document.getElementById('add-department-form');
        if (form) {
            if (show) {
                form.classList.remove('hidden');
            } else {
                form.classList.add('hidden');
            }
        }
    }

    function fetchDepartments() {
        console.log('Fetching departments... Check if #locations-departments-list exists:', $('#locations-departments-list').length);
        return $.ajax({
            url: "{{ route('adminSettings.departments.fetch') }}",
            method: "GET",
            dataType: 'json',
            success: function (response) {
                console.log('Departments fetched:', response);

                const listContainer = $('#locations-departments-list');
                if (listContainer.length === 0) {
                    console.error('CRITICAL: #locations-departments-list element not found in DOM!');
                    return;
                }

                // Update the departments list container regardless of visibility
                // This ensures it's populated when the tab becomes matching
                let html = '';
                if (Array.isArray(response) && response.length === 0) {
                    html = '<div class="text-center py-8 text-gray-500 col-span-full">No departments found.</div>';
                } else if (Array.isArray(response)) {
                    response.forEach(function (dept) {
                        let iconClass = getIconClass(dept.icon);
                        html += `
                            <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-${dept.color}-100 text-${dept.color}-600">
                                        <i class="bi ${iconClass} text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900">${dept.name}</h4>
                                        <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Active</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    console.error('Invalid response format:', response);
                    html = '<div class="text-center py-8 text-red-500 col-span-full">Error: Invalid data received.</div>';
                }
                console.log('Updating HTML of #locations-departments-list');
                listContainer.html(html);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching departments:', error, xhr.responseText);
                $('#locations-departments-list').html('<div class="text-center py-8 text-red-500 col-span-full">Failed to load departments. Please try again.</div>');
            }
        });
    }

    function getIconClass(icon) {
        if (!icon) return 'bi-box';
        if (icon.startsWith('bi-') || icon.startsWith('bx-')) return icon;
        if (['box', 'fire', 'cake', 'brush', 'tools', 'people', 'shop', 'truck'].includes(icon)) {
            return 'bi-' + icon;
        }
        return 'bi-box';
    }

    // --- LOCATIONS LOGIC ---
    function toggleAddLocationForm(show) {
        const form = document.getElementById('add-location-form');
        if (show) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }

    function toggleAssignDepartmentModal(show) {
        const modal = document.getElementById('assign-department-modal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    function openAssignDepartmentModal(branchId, branchName, assignedDepartmentIds) {
        $('#assign_branch_id').val(branchId);
        $('#assign_branch_name').text(branchName);

        // Fetch all departments first
        fetchDepartments().then(function (departments) {
            let html = '';
            if (departments.length === 0) {
                html = '<p class="text-gray-500 text-center">No departments created yet.</p>';
            } else {
                departments.forEach(dept => {
                    let isChecked = assignedDepartmentIds.includes(dept.id) ? 'checked' : '';
                    let iconClass = getIconClass(dept.icon);
                    html += `
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="department_ids[]" value="${dept.id}" class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]" ${isChecked}>
                            <div class="ml-3 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-${dept.color}-100 text-${dept.color}-600 text-xs">
                                     <i class="bi ${iconClass}"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">${dept.name}</span>
                            </div>
                        </label>
                    `;
                });
            }
            $('#assign_departments_list').html(html);
            toggleAssignDepartmentModal(true);
        });
    }

    $(document).ready(function () {
        console.log('Locations ready');
        fetchBranches();
        fetchBranchTypes();

        // Handle Department Create Form
        $('#createDepartmentForm').on('submit', function (e) {
            e.preventDefault();
            const formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: $(this).find('[name="name"]').val(),
                color: $(this).find('[name="color"]:checked').val(),
                icon: $(this).find('[name="icon"]:checked').val()
            };

            const btn = $(this).find('button[type="submit"]');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="animate-spin bi bi-arrow-repeat"></i> Creating...');

            $.ajax({
                url: "{{ route('advancedPlanner.storeDepartment') }}",
                method: "POST",
                data: formData,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Department created successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    toggleAddDepartmentForm(false);
                    $('#createDepartmentForm')[0].reset();
                    fetchDepartments();
                },
                error: function (xhr) {
                    // ... error handling ...
                },
                complete: function () {
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Handle Assign Department Form
        $('#assignDepartmentForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('adminSettings.branches.assignDepartments') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Departments assigned successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    toggleAssignDepartmentModal(false);
                    fetchBranches(); // Refresh list to show assignments
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to assign departments.',
                    });
                }
            });
        });

        $('#createLocationForm').on('submit', function (e) {
            // ... (existing code for create location form) ...
            e.preventDefault();
            $.ajax({
                url: "{{ route('adminSettings.branches.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Location added successfully!',
                    });
                    toggleAddLocationForm(false);
                    $('#createLocationForm')[0].reset();
                    fetchBranches();
                },
                error: function (xhr) {
                    // ... error handling ...
                }
            });
        });
    });

    // ... toggleBranchTypeModal and its form submit ...
    function toggleBranchTypeModal(show) {
        const modal = document.getElementById('branch-type-modal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    $(document).ready(function () {
        $('#createBranchTypeForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('adminSettings.branchTypes.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Branch Type added successfully!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    toggleBranchTypeModal(false);
                    $('#createBranchTypeForm')[0].reset();
                    fetchBranchTypes();
                },
                error: function (xhr) {
                    // ... error handling ...
                }
            });
        });
    });


    function fetchBranches() {
        $.ajax({
            url: "{{ route('adminSettings.branches.fetch') }}",
            method: "GET",
            success: function (response) {
                let html = '';
                if (response.length === 0) {
                    html = '<div class="text-center py-8 text-gray-500">No locations found.</div>';
                } else {
                    response.forEach(function (branch) {
                        html += renderBranchCard(branch);
                    });
                }
                $('#locations-list').html(html);
            },
            error: function (xhr) {
                console.error(xhr);
                $('#locations-list').html('<div class="text-center py-8 text-red-500">Failed to load locations: ' + xhr.statusText + '</div>');
            }
        });
    }

    // ... fetchBranchTypes, setDefault, toggleBranchStatus ...
    function fetchBranchTypes() {
        $.ajax({
            url: "{{ route('adminSettings.branchTypes.fetch') }}",
            method: "GET",
            success: function (response) {
                let options = '<option value="">Select Type</option>';
                response.forEach(function (type) {
                    options += `<option value="${type.id}">${type.name}</option>`;
                });
                $('#type').html(options);
            }
        });
    }

    function setDefault(branchId) {
        $.ajax({
            url: "{{ route('adminSettings.branches.setDefault') }}",
            method: "POST",
            data: {
                id: branchId,
                _token: "{{ csrf_token() }}"
            },
            success: function (response) {
                // ... success ...
                Swal.fire({
                    icon: 'success',
                    title: 'Branch set as default successfully!',
                    showConfirmButton: false,
                    timer: 1500
                });
                fetchBranches();
            },
            error: function (xhr) {
                // ... error ...
            }
        });
    }

    function toggleBranchStatus(branchId, newStatus) {
        // ... existing implementation ...
        let action = newStatus ? 'activate' : 'deactivate';
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${action} this location?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D4A017',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, ' + action + ' it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('adminSettings.branches.toggleStatus') }}",
                    method: "POST",
                    data: {
                        id: branchId,
                        status: newStatus,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        fetchBranches();
                    },
                    error: function (xhr) {
                        // ... error ...
                    }
                });
            }
        });
    }

    function renderBranchCard(branch) {
        let typeName = branch.type ? branch.type.name : 'Unknown';
        let badgeColor = getBadgeColor(typeName);
        let statusBadge = branch.status == 1 ?
            `<span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Active
            </span>` :
            `<span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                Inactive
            </span>`;

        let defaultBadge = branch.is_default == 1 ?
            `<span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Default
            </span>` : '';

        // ACTIONS
        let actions = `
            <div class="flex items-center gap-2">
                <button onclick="openAssignDepartmentModal(${branch.id}, '${branch.name}', [${branch.departments ? branch.departments.map(d => d.id).join(',') : ''}])" 
                    class="inline-flex items-center px-3 py-1 border border-[#D4A017] shadow-sm text-xs font-medium rounded-md text-[#D4A017] bg-white hover:bg-amber-50 focus:outline-none">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Assign Depts
                </button>
                
                ${branch.is_default != 1 ? `
                <button onclick="setDefault(${branch.id})" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                     <i class="bi bi-star mr-1"></i> Default
                </button>` : ''}
                
                ${branch.status == 1 ? `
                <button onclick="toggleBranchStatus(${branch.id}, 0)" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none">
                     <i class="bi bi-power mr-1"></i>
                </button>` : `
                <button onclick="toggleBranchStatus(${branch.id}, 1)" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-green-700 bg-white hover:bg-green-50 focus:outline-none">
                     <i class="bi bi-power mr-1"></i>
                </button>`}
            </div>
        `;

        let iconHtml = (branch.type && branch.type.icon) ? `<i class="${branch.type.icon} mr-1"></i>` : '';

        // DEPARTMENTS BADGES
        let departmentsHtml = '';
        if (branch.departments && branch.departments.length > 0) {
            departmentsHtml = '<div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-2">';
            branch.departments.forEach(dept => {
                let iconClass = getIconClass(dept.icon);
                departmentsHtml += `
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${dept.color}-100 text-${dept.color}-800 border border-${dept.color}-200">
                        <i class="bi ${iconClass} mr-1.5"></i>
                        ${dept.name}
                    </span>
                `;
            });
            departmentsHtml += '</div>';
        } else {
            departmentsHtml = '<div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400 italic">No departments assigned</div>';
        }

        return `
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex flex-col gap-4">
                    <div class="flex items-start justify-between">
                         <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-bold text-gray-900">${branch.name}</h3>
                                ${statusBadge}
                                ${defaultBadge}
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <span class="px-2 py-0.5 rounded bg-gray-100 border border-gray-200 text-xs font-mono font-medium text-gray-600">
                                    ${branch.code}
                                </span>
                                <span class="flex items-center gap-1 px-2 py-0.5 rounded ${badgeColor} text-xs font-medium">
                                    ${iconHtml} ${typeName}
                                </span>
                            </div>
                        </div>
                        ${actions}
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                        <!-- Address -->
                        <div class="flex gap-3">
                            <div class="mt-1 text-gray-400"><i class="bi bi-geo-alt"></i></div>
                            <div>
                                <p class="text-gray-900 font-medium">${branch.street_address || '-'}</p>
                                <p class="text-gray-500">
                                    ${branch.city || ''}
                                    ${branch.province ? ', ' + branch.province : ''}
                                </p>
                            </div>
                        </div>
                        
                         <!-- Contact -->
                        <div class="flex gap-3">
                            <div class="mt-1 text-gray-400"><i class="bi bi-person"></i></div>
                            <div>
                                <p class="text-gray-900 font-medium">${branch.contact_person || '-'}</p>
                                <p class="text-gray-500">${branch.contact_person_phone || '-'}</p>
                            </div>
                        </div>

                         <!-- GL -->
                        <div class="flex gap-3">
                            <div class="mt-1 text-gray-400"><i class="bi bi-wallet2"></i></div>
                            <div>
                                <p class="text-gray-500"><span class="font-medium text-gray-900">Cash:</span> ${branch.cash_account || '-'}</p>
                                <p class="text-gray-500"><span class="font-medium text-gray-900">Inv:</span> ${branch.bank_account || '-'}</p>
                            </div>
                        </div>
                    </div>
                    
                    ${departmentsHtml}
                </div>
            </div>
        `;
    }

    function getBadgeColor(type) {
        if (!type) return 'bg-gray-100 text-gray-800';
        type = type.toLowerCase();
        if (type.includes('hq') || type.includes('head')) return 'bg-purple-100 text-purple-800';
        if (type.includes('branch')) return 'bg-blue-100 text-blue-800';
        if (type.includes('production')) return 'bg-orange-100 text-orange-800';
        if (type.includes('warehouse')) return 'bg-green-100 text-green-800';
        return 'bg-gray-100 text-gray-800';
    }
</script>