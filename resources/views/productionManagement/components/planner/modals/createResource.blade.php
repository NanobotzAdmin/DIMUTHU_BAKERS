<!-- Create Resource Modal (React-Design Match) -->
<div id="resource-modal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity opacity-0"
        id="resource-modal-backdrop"></div>

    <!-- Modal Panel Container -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <!-- Modal Panel -->
            <div id="resource-modal-content"
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all scale-95 opacity-0 sm:my-8 sm:w-full sm:max-w-[550px]">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 id="modal-title" class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <!-- Icon & Title set by JS -->
                            </h3>
                            <p id="modal-desc" class="text-sm text-gray-500 mt-1">
                                <!-- Desc set by JS -->
                            </p>
                        </div>
                        <button onclick="ResourceModal.close()"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="space-y-4 px-6 py-4">
                    <form id="resource-form" onsubmit="ResourceModal.handleSave(event)">
                        <input type="hidden" id="rm-id">

                        <!-- Resource Name -->
                        <div class="space-y-2">
                            <label for="rm-name"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Resource
                                Name *</label>
                            <input type="text" id="rm-name" required oninput="ResourceModal.updatePreview()"
                                placeholder="e.g., Deck Oven 1, KitchenAid Mixer A"
                                class="flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:border-transparent disabled:cursor-not-allowed disabled:opacity-50">
                        </div>

                        <!-- Resource Type -->
                        <div class="space-y-2">
                            <label for="rm-type"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Resource
                                Type *</label>
                            <select id="rm-type" onchange="ResourceModal.updatePreview()"
                                class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:border-transparent disabled:cursor-not-allowed disabled:opacity-50">
                                <option value="oven">Oven</option>
                                <option value="mixer">Mixer</option>
                                <option value="proofer">Proofer</option>
                                <option value="workstation">Workstation</option>
                                <option value="chiller">Chiller</option>
                            </select>
                        </div>

                        <!-- Department -->
                        <div class="space-y-2">
                            <label for="rm-dept"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Department
                                *</label>
                            <select id="rm-dept" onchange="ResourceModal.updatePreview()"
                                class="flex h-10 w-full items-center justify-between rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:border-transparent disabled:cursor-not-allowed disabled:opacity-50">
                                <!-- Populated by JS -->
                            </select>
                        </div>

                        <!-- Capacity -->
                        <div class="space-y-2">
                            <label for="rm-capacity"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Default
                                Capacity</label>
                            <div class="flex items-center gap-3">
                                <input type="range" id="rm-capacity-range" min="0" max="200" step="10" value="100"
                                    oninput="ResourceModal.syncCapacity(this.value)"
                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-[#D4A017]">

                                <div class="flex items-center gap-1 min-w-[70px]">
                                    <input type="number" id="rm-capacity-input" min="0" max="200" value="100"
                                        oninput="ResourceModal.syncCapacity(this.value)"
                                        class="w-16 h-10 text-center rounded-md border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:border-transparent">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 flex items-start gap-1">
                                <i data-lucide="info" class="w-3 h-3 mt-0.5 shrink-0"></i>
                                <span>100% = normal capacity. Set higher for overtime availability or lower for
                                    maintenance periods.</span>
                            </p>
                        </div>

                        <!-- Available Shifts -->
                        <div class="space-y-2">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Available
                                Shifts</label>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="shift-morning" value="morning"
                                        onchange="ResourceModal.updatePreview()"
                                        class="h-4 w-4 rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="shift-morning" class="text-sm cursor-pointer flex-1">Morning (4 AM - 12
                                        PM)</label>
                                    <span id="badge-morning"
                                        class="hidden inline-flex items-center rounded-full border border-green-300 bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">Active</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="shift-afternoon" value="afternoon"
                                        onchange="ResourceModal.updatePreview()"
                                        class="h-4 w-4 rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="shift-afternoon" class="text-sm cursor-pointer flex-1">Afternoon (12 PM
                                        - 8 PM)</label>
                                    <span id="badge-afternoon"
                                        class="hidden inline-flex items-center rounded-full border border-green-300 bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">Active</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="shift-night" value="night"
                                        onchange="ResourceModal.updatePreview()"
                                        class="h-4 w-4 rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="shift-night" class="text-sm cursor-pointer flex-1">Night (8 PM - 4
                                        AM)</label>
                                    <span id="badge-night"
                                        class="hidden inline-flex items-center rounded-full border border-green-300 bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">Active</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="space-y-2">
                            <label for="rm-notes"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">Notes
                                / Specifications (Optional)</label>
                            <textarea id="rm-notes" rows="3"
                                placeholder="e.g., Max temp: 450°F, 4 deck levels, Steam injection capable"
                                class="flex min-h-[80px] w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:border-transparent disabled:cursor-not-allowed disabled:opacity-50 resize-none"></textarea>
                        </div>

                        <!-- Preview -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2">
                            <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <i id="preview-icon" data-lucide="flame" class="w-4 h-4 text-orange-600"></i>
                                <span>Preview</span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <div><span class="font-medium">Name:</span> <span id="prev-name">(Not set)</span></div>
                                <div><span class="font-medium">Type:</span> <span id="prev-type">Oven</span></div>
                                <div><span class="font-medium">Department:</span> <span id="prev-dept">(Not set)</span>
                                </div>
                                <div><span class="font-medium">Capacity:</span> <span id="prev-capacity">100</span>%
                                </div>
                                <div><span class="font-medium">Shifts:</span> <span id="prev-shifts">0</span> active
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 flex flex-row-reverse gap-3 rounded-b-lg border-t bg-gray-50/50">
                    <button type="button"
                        onclick="document.getElementById('resource-form').dispatchEvent(new Event('submit'))"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-[#D4A017] text-primary-foreground hover:bg-[#B8860B] h-10 px-4 py-2 text-white">
                        <i id="save-icon" data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        <span id="save-text">Add Resource</span>
                    </button>
                    <button type="button" onclick="ResourceModal.close()"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ResourceModal = {
        // Configuration map for types
        types: {
            oven: { label: 'Oven', icon: 'flame', color: 'text-orange-600' },
            mixer: { label: 'Mixer', icon: 'package', color: 'text-purple-600' },
            proofer: { label: 'Proofer', icon: 'layers', color: 'text-blue-600' },
            workstation: { label: 'Workstation', icon: 'users', color: 'text-green-600' },
            chiller: { label: 'Chiller', icon: 'refrigerator', color: 'text-cyan-600' }
        },

        onSaveCallback: null,

        init: function () {
            // Setup Typeahead/Search for Name
            const nameInput = document.getElementById('rm-name');
            const suggestionsList = document.createElement('ul');
            suggestionsList.id = 'rm-name-suggestions';
            suggestionsList.className = 'absolute z-50 w-full bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto hidden';
            nameInput.parentNode.appendChild(suggestionsList);

            nameInput.addEventListener('input', debounce(async (e) => {
                const query = e.target.value;
                this.updatePreview();
                if (query.length < 2) {
                    suggestionsList.classList.add('hidden');
                    return;
                }

                try {
                    const response = await fetch(`/api/production/search-resources?q=${encodeURIComponent(query)}`);
                    const resources = await response.json();

                    suggestionsList.innerHTML = '';
                    if (resources.length > 0) {
                        resources.forEach(res => {
                            const li = document.createElement('li');
                            li.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm';
                            li.innerText = res.name;
                            li.onclick = () => {
                                nameInput.value = res.name;
                                document.getElementById('rm-type').value = res.type || 'oven';
                                document.getElementById('rm-capacity-input').value = res.capacity || 100;
                                document.getElementById('rm-capacity-range').value = res.capacity || 100;
                                if (res.pln_department_id) document.getElementById('rm-dept').value = res.pln_department_id;
                                suggestionsList.classList.add('hidden');
                                this.updatePreview();
                            };
                            suggestionsList.appendChild(li);
                        });
                        suggestionsList.classList.remove('hidden');
                    } else {
                        // Optional: Show "New Resource" text or keep hidden
                        suggestionsList.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Error searching resources:', error);
                }
            }, 300));

            // Hide suggestions on click outside
            document.addEventListener('click', (e) => {
                if (!nameInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.classList.add('hidden');
                }
            });
        },

        open: async function (resource, departments, onSaveCallback) {
            this.onSaveCallback = onSaveCallback;
            const modal = document.getElementById('resource-modal');
            const content = document.getElementById('resource-modal-content');

            // Initialize if not done
            if (!document.getElementById('rm-name-suggestions')) {
                this.init();
            }

            // Fetch Departments Dynamically
            try {
                const response = await fetch('/api/production/branch-departments');
                const fetchedDepartments = await response.json();

                const deptSelect = document.getElementById('rm-dept');
                deptSelect.innerHTML = fetchedDepartments.map(d => `<option value="${d.id}">${d.name}</option>`).join('');

                // Default Dept (First one) if adding
                if (!resource && fetchedDepartments.length > 0) deptSelect.value = fetchedDepartments[0].id;
            } catch (error) {
                console.error('Failed to load departments', error);
                // Fallback to passed departments if fetch fails?
                const deptSelect = document.getElementById('rm-dept');
                if (departments && departments.length) {
                    deptSelect.innerHTML = departments.map(d => `<option value="${d.id}">${d.name}</option>`).join('');
                } else {
                    deptSelect.innerHTML = '<option value="">No Departments Found</option>';
                }
            }


            // Reset or Fill Data
            if (resource) {
                // Edit Mode
                document.getElementById('rm-id').value = resource.id;
                document.getElementById('rm-name').value = resource.name;
                document.getElementById('rm-type').value = resource.type;
                document.getElementById('rm-dept').value = resource.departmentId;
                document.getElementById('rm-notes').value = resource.notes || '';

                // Capacity
                this.syncCapacity(resource.capacity || 100);

                // Shifts
                ['morning', 'afternoon', 'night'].forEach(shift => {
                    document.getElementById(`shift-${shift}`).checked = resource.shifts?.includes(shift) || false;
                });

                // UI Text & Icons
                document.getElementById('modal-title').innerHTML = '<i data-lucide="edit" class="w-5 h-5 text-[#D4A017]"></i> Edit Resource';
                document.getElementById('modal-desc').innerText = 'Update the resource details and capacity settings.';
                document.getElementById('save-text').innerText = 'Update Resource';
                document.getElementById('save-icon').setAttribute('data-lucide', 'edit');
            } else {
                // Add Mode
                document.getElementById('resource-form').reset();
                document.getElementById('rm-id').value = '';
                this.syncCapacity(100);

                // Default shifts
                document.getElementById('shift-morning').checked = true;
                document.getElementById('shift-afternoon').checked = true;
                document.getElementById('shift-night').checked = false;

                // UI Text & Icons
                document.getElementById('modal-title').innerHTML = '<i data-lucide="plus" class="w-5 h-5 text-[#D4A017]"></i> Add New Resource';
                document.getElementById('modal-desc').innerText = 'Create a new production resource to use in scheduling.';
                document.getElementById('save-text').innerText = 'Add Resource';
                document.getElementById('save-icon').setAttribute('data-lucide', 'plus');
            }

            // Show Modal
            modal.classList.remove('hidden');
            // Small delay for animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            this.updatePreview();
            lucide.createIcons();
        },

        close: function () {
            const modal = document.getElementById('resource-modal');
            const backdrop = document.getElementById('resource-modal-backdrop');
            const content = document.getElementById('resource-modal-content');

            backdrop.classList.add('opacity-0');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        },

        syncCapacity: function (val) {
            document.getElementById('rm-capacity-range').value = val;
            document.getElementById('rm-capacity-input').value = val;
            this.updatePreview();
        },

        updatePreview: function () {
            const name = document.getElementById('rm-name').value || '(Not set)';
            const type = document.getElementById('rm-type').value;
            const deptId = document.getElementById('rm-dept').value;
            const deptText = document.getElementById('rm-dept').options[document.getElementById('rm-dept').selectedIndex]?.text || '(Not set)';
            const cap = document.getElementById('rm-capacity-input').value;

            const config = this.types[type] || this.types['oven'];

            // Update Preview Elements
            document.getElementById('prev-name').innerText = name;
            document.getElementById('prev-type').innerText = config.label;
            document.getElementById('prev-dept').innerText = deptText;
            document.getElementById('prev-capacity').innerText = cap;

            // Icon Update
            document.getElementById('preview-icon').setAttribute('data-lucide', config.icon);
            document.getElementById('preview-icon').className = `w-4 h-4 ${config.color}`;

            // Handle Shifts Badges & Count
            let activeShifts = 0;
            ['morning', 'afternoon', 'night'].forEach(shift => {
                const isChecked = document.getElementById(`shift-${shift}`).checked;
                const badge = document.getElementById(`badge-${shift}`);
                if (isChecked) {
                    badge.classList.remove('hidden');
                    activeShifts++;
                } else {
                    badge.classList.add('hidden');
                }
            });
            document.getElementById('prev-shifts').innerText = activeShifts;

            lucide.createIcons();
        },

        handleSave: async function (e) {
            e.preventDefault();
            const id = document.getElementById('rm-id').value;

            const shifts = [];
            if (document.getElementById('shift-morning').checked) shifts.push('morning');
            if (document.getElementById('shift-afternoon').checked) shifts.push('afternoon');
            if (document.getElementById('shift-night').checked) shifts.push('night');

            const payload = {
                name: document.getElementById('rm-name').value,
                type: document.getElementById('rm-type').value,
                pln_department_id: document.getElementById('rm-dept').value,
                capacity: parseInt(document.getElementById('rm-capacity-input').value),
                shifts: shifts,
                notes: document.getElementById('rm-notes').value
            };

            // Use AJAX to save
            try {
                // Determine Endpoint - For now we only implemented store-resource which handles create/link
                // If we want to support pure EDIT of ID, we need an update endpoint.
                // Assuming 'Add Resource' always uses store-resource
                const response = await fetch('/api/production/store-resource', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (result.success) {
                    if (this.onSaveCallback) {
                        this.onSaveCallback({
                            ...payload,
                            id: result.resource.id // Use returned ID
                        });
                    }
                    this.close();
                    // Optional: Show Toast
                } else {
                    alert('Error saving resource: ' + result.message);
                }
            } catch (error) {
                console.error('Save error:', error);
                alert('Failed to save resource.');
            }
        }
    };

    // Helper debounce function
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
</script>