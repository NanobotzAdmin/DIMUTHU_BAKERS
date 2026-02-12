@php
    // Get all resources flattened for stats
    $allResources = $departments->flatMap(function ($dept) {
        return $dept['resources'] ?? []; // Handle array structure from controller
    });

    // Check if resources are arrays or objects (Controller returns arrays for 'departments')
    // The controller code: $departments = $departmentsRaw->map(function ($dept) { return [ ... 'resources' => ... ] })
    // So $departments is a Collection of Arrays. And resources are arrays inside it.

    $total = $allResources->count();
    $active = $allResources->where('status', 'active')->count();
    // Adjusted logic based on 'status' string in controller: 'active' : 'inactive'
    // But verify if controller maps status to strings.
    // Controller line 51: 'status' => $res->status == 1 ? 'active' : 'inactive'
    // So status is a string 'active' or 'inactive'.

    // However, the original prompt had 'maintenance' and 'offline' which might not be in the map yet.
    // I will stick to the controller's current data structure but anticipate future changes or map strictly to what's there.
    // For now, let's just count 'active' and 'inactive'.

    $maintenance = 0; // Not currently in controller logic
    $offline = $allResources->where('status', 'inactive')->count();

    $stats = [
        'total' => $total,
        'active' => $active,
        'maintenance' => $maintenance,
        'offline' => $offline,
    ];

    // Resource constants for display
    $types = [
        'oven' => ['color' => 'text-orange-600', 'bg' => 'bg-orange-50', 'label' => 'Oven'],
        'mixer' => ['color' => 'text-purple-600', 'bg' => 'bg-purple-50', 'label' => 'Mixer'],
        'proofer' => ['color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'label' => 'Proofer'],
        'workstation' => ['color' => 'text-green-600', 'bg' => 'bg-green-50', 'label' => 'Workstation'],
        'chiller' => ['color' => 'text-cyan-600', 'bg' => 'bg-cyan-50', 'label' => 'Chiller'],
    ];
@endphp

<div class="flex-1 flex flex-col h-full bg-[#F5F5F7] overflow-hidden">
    {{-- Header with Stats --}}
    <div class="bg-white border-b border-gray-200 px-6 py-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Production Resources</h2>
                <p class="text-sm text-gray-600">Manage ovens, mixers, proofers, and other equipment</p>
            </div>
            <button id="btn-open-add-resource"
                class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-md flex items-center transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Add Resource
            </button>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                <div class="text-2xl font-semibold text-blue-900" id="stat-total">{{ $stats['total'] }}</div>
                <div class="text-xs text-blue-700">Total Resources</div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3">
                <div class="text-2xl font-semibold text-green-900" id="stat-active">{{ $stats['active'] }}</div>
                <div class="text-xs text-green-700">Active</div>
            </div>
            <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3">
                <div class="text-2xl font-semibold text-amber-900" id="stat-maintenance">{{ $stats['maintenance'] }}
                </div>
                <div class="text-xs text-amber-700">In Maintenance</div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                <div class="text-2xl font-semibold text-red-900" id="stat-offline">{{ $stats['offline'] }}</div>
                <div class="text-xs text-red-700">Offline</div>
            </div>
        </div>
    </div>

    {{-- Filters Toolbar --}}
    <div class="bg-white border-b border-gray-200 px-6 py-3">
        <div class="flex items-center gap-3">
            {{-- Search --}}
            <div class="relative flex-1 max-w-md bg-gray-100 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <circle cx="11" cy="11" r="8" />
                    <path d="m21 21-4.3-4.3" />
                </svg>
                <input type="text" id="resource-search" placeholder="Search resources..."
                    class="w-full pl-9 h-9 text-sm border-gray-300 rounded-md focus:border-[#D4A017] focus:ring-[#D4A017]" />
            </div>

            {{-- Branch Filter --}}
            <select id="filter-branch" class="w-[200px] h-9 bg-gray-100 px-2 text-sm border-gray-300 rounded-md focus:border-[#D4A017] focus:ring-[#D4A017]">
                <option value="">Select Branch</option>
                @foreach($userBranches as $branch)
                    <option value="{{ $branch['id'] }}" {{ (auth()->user()->current_branch_id == $branch['id']) ? 'selected' : '' }}>
                        {{ $branch['name'] }}
                    </option>
                @endforeach
            </select>

            {{-- Department Filter --}}
            <select id="filter-department" class="w-[180px] h-9 bg-gray-100 px-2 text-sm border-gray-300 rounded-md focus:border-[#D4A017] focus:ring-[#D4A017]">
                <option value="all">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept['id'] }}">{{ $dept['name'] }}</option>
                @endforeach
            </select>

            {{-- Type Filter --}}
            <select id="filter-type" class="w-[160px] h-9 bg-gray-100 px-2 text-sm border-gray-300 rounded-md focus:border-[#D4A017] focus:ring-[#D4A017]">
                <option value="all">All Types</option>
                @foreach($types as $key => $config)
                    <option value="{{ $key }}">{{ $config['label'] }}</option>
                @endforeach
            </select>

            {{-- Results Count --}}
            <div class="text-sm text-gray-600">
                <span id="visible-count">{{ $stats['total'] }}</span> resource(s)
            </div>
        </div>
    </div>

    {{-- Resources Grid --}}
    <div class="flex-1 overflow-y-auto px-6 py-6" id="resources-container">
        @if(count($departments) === 0)
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-300 mx-auto mb-4"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.09a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No resources found</h3>
                    <p class="text-sm text-gray-600 mb-4">Get started by adding your first production resource</p>
                    <button onclick="$('#btn-open-add-resource').click()" class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-md">
                        Add Resource
                    </button>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @foreach($departments as $dept)
                    <div class="department-section" data-dept-id="{{ $dept['id'] }}">
                        {{-- Department Header --}}
                        <div class="flex items-center gap-2 mb-3">
                             <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-600"><rect width="8" height="8" x="2" y="2" rx="2"/><path d="M14 2c.6.5 1.2 1 2 2a1.8 1.8 0 0 1 .8 1.6V8a2 2 0 0 1-2 2h-2c-.6-.5-1.2-1-2-2a1.8 1.8 0 0 1-.8-1.6V5c0-1.1.9-2 2-2Z"/><path d="M20 14c.5-.6 1-1.2 2-2a1.8 1.8 0 0 0-1.6-.8H18a2 2 0 0 0-2 2v2c.5.6 1 1.2 2 2a1.8 1.8 0 0 0 1.6.8h2.4a2 2 0 0 0 2-2Z"/><path d="M14 14c-.6.5-1.2 1-2 2a1.8 1.8 0 0 0-.8 1.6V20a2 2 0 0 0 2 2h2c.6-.5 1.2-1 2-2a1.8 1.8 0 0 0 .8-1.6V17c0-1.1-.9-2-2-2Z"/></svg>
                            <h3 class="font-semibold text-gray-900">{{ $dept['name'] }}</h3>
                            <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 dept-count">
                                {{ count($dept['resources']) }} resource{{ count($dept['resources']) !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        {{-- Resource Cards Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($dept['resources'] as $resource)
                                @php
                                    $typeKey = strtolower($resource['type'] ?? 'other');
                                    $typeConfig = $types[$typeKey] ?? ['color' => 'text-gray-600', 'bg' => 'bg-gray-50', 'label' => ucfirst($resource['type'] ?? 'Other')];
                                    $utilization = $resource['currentUtilization'] ?? 0;

                                    // Status Badge Logic
                                    $statusClass = 'bg-green-50 text-green-700 border-green-300';
                                    $statusLabel = 'Active';

                                    // Map string status to labels (Controller returns 'active'/'inactive')
                                    if (isset($resource['status'])) {
                                        if ($resource['status'] == 'inactive') {
                                            $statusClass = 'bg-red-50 text-red-700 border-red-300';
                                            $statusLabel = 'Offline';
                                        } elseif ($resource['status'] == 'maintenance') {
                                            $statusClass = 'bg-amber-50 text-amber-700 border-amber-300';
                                            $statusLabel = 'Maintenance';
                                        }
                                    }

                                    // Utilization Color
                                    $utilColor = 'text-green-600';
                                    $utilBg = 'bg-green-500';
                                    if ($utilization >= 90) {
                                        $utilColor = 'text-red-600';
                                        $utilBg = 'bg-red-500';
                                    } elseif ($utilization >= 70) {
                                        $utilColor = 'text-amber-600';
                                        $utilBg = 'bg-amber-500';
                                    }
                                @endphp

                                <div class="resource-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow group"
                                     data-name="{{ strtolower($resource['name']) }}"
                                     data-type="{{ $typeKey }}"
                                     data-department-id="{{ $dept['id'] }}"
                                     data-status="{{ $resource['status'] }}">

                                    {{-- Card Header --}}
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <div class="w-10 h-10 rounded-lg {{ $typeConfig['bg'] }} flex items-center justify-center shrink-0">
                                                @if($typeKey == 'oven')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $typeConfig['color'] }}"><path d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.1.2-2.2.5-3.3.3-1.07 1.5-2.2 2.5-3.2"/></svg>
                                                @elseif($typeKey == 'mixer')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $typeConfig['color'] }}"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                                @elseif($typeKey == 'proofer')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $typeConfig['color'] }}"><path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"/><path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"/><path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"/></svg>
                                                @elseif($typeKey == 'workstation')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $typeConfig['color'] }}"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                @elseif($typeKey == 'chiller')
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $typeConfig['color'] }}"><path d="M5 6a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6Z"/><path d="M5 10h14"/><path d="M15 2v20"/></svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $typeConfig['color'] }}"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 truncate" title="{{ $resource['name'] }}">{{ $resource['name'] }}</h4>
                                                <p class="text-xs text-gray-600">{{ $typeConfig['label'] }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <!-- Edit/Delete functionality placeholders -->
                                            <button class="h-8 w-8 p-0 flex items-center justify-center rounded-md hover:bg-gray-100 text-gray-600" title="Edit">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                            </button>
                                            <button class="h-8 w-8 p-0 flex items-center justify-center rounded-md hover:bg-red-50 text-red-600" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Status & Capacity --}}
                                    <div class="space-y-2 mb-3">
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">Status</span>
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 {{ $statusClass }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-gray-600">Capacity</span>
                                            <span class="font-medium text-gray-900">{{ $resource['capacity'] }}%</span>
                                        </div>
                                    </div>

                                    {{-- Current Utilization --}}
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-600">Current Utilization</span>
                                            <span class="font-semibold {{ $utilColor }}">{{ $utilization }}%</span>
                                        </div>
                                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full transition-all {{ $utilBg }}" style="width: {{ min($utilization, 100) }}%"></div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Empty Search Result Message (Hidden by default) --}}
                <div id="no-results-message" class="hidden text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-300 mx-auto mb-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No matching resources</h3>
                    <p class="text-sm text-gray-600">Try adjusting your filters</p>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Add Resource Modal --}}
<div id="modal-add-resource" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity" onclick="$('#modal-add-resource').addClass('hidden')"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6 animate-in fade-in zoom-in duration-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add New Resource</h3>
                <button onclick="$('#modal-add-resource').addClass('hidden')" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 18 18"/></svg>
                </button>
            </div>
            
            <form id="form-add-resource">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <select id="modal-branch-select" name="branch_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                            <option value="">Select Branch</option>
                            @foreach($userBranches as $branch)
                                <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="modal-dept-select" name="department_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                            <option value="">Select Branch First</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resource Name</label>
                        <input type="text" name="name" required placeholder="e.g., Deck Oven 3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                            <option value="">Select Type</option>
                            @foreach($types as $key => $config)
                                <option value="{{ $key }}">{{ $config['label'] }}</option>
                            @endforeach
                             <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                        <input type="number" name="capacity" min="0" step="0.1" placeholder="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4A017] focus:border-[#D4A017]">
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <button type="button" onclick="$('#modal-add-resource').addClass('hidden')" class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-[#D4A017] text-white px-4 py-2 rounded-md hover:bg-[#B8860B] transition-colors">
                        Add Resource
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- jQuery for Interactivity --}}
<script>
$(document).ready(function() {
    // Interactivity Logic
    const $searchInput = $('#resource-search');
    const $deptFilter = $('#filter-department');
    const $typeFilter = $('#filter-type');
    const $branchFilter = $('#filter-branch');
    const $cards = $('.resource-card'); // Initial cards
    const $container = $('#resources-container');
    const $visibleCount = $('#visible-count');
    
    // icons map
    const typeIcons = {
        oven: '<svg ...>...</svg>', // Simplified for brevity in this JS var, ideally use a function
        mixer: '<svg ...>...</svg>',
        proofer: '<svg ...>...</svg>',
        workstation: '<svg ...>...</svg>',
        chiller: '<svg ...>...</svg>',
        other: '<svg ...>...</svg>'
    };

    function loadDepartments(branchId, targetSelectId = '#filter-department') {
        if(!branchId) return;
        
        $.get("{{ route('advancedPlanner.fetchBranchDepartments') }}", { branch_id: branchId })
            .done(function(data) {
                const $select = $(targetSelectId);
                $select.empty();
                $select.append('<option value="">Select Department</option>');
                if(targetSelectId === '#filter-department') {
                     $select.append('<option value="all" selected>All Departments</option>');
                }
                
                data.forEach(dept => {
                    $select.append(`<option value="${dept.id}">${dept.name}</option>`);
                });

                // Auto-refresh grid if main filter
                if(targetSelectId === '#filter-department') {
                    fetchAndRenderResources();
                }
            });
    }

    function fetchAndRenderResources() {
        const branchId = $branchFilter.val();
        const deptId = $deptFilter.val();

        if(!branchId) {
             return; 
        }

        $.get("{{ route('advancedPlanner.fetchResources') }}", { branch_id: branchId, department_id: deptId })
            .done(function(resources) {
                renderGrid(resources);
            });
    }

    function renderGrid(resources) {
        $container.empty();
        
        if(resources.length === 0) {
            $container.append(`
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No resources found</h3>
                        <button onclick="$('#btn-open-add-resource').click()" class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-md">Add Resource</button>
                    </div>
                </div>
            `);
            $visibleCount.text(0);
            return;
        }

        // Check if we should group by department (when 'All Departments' is selected)
        const isGrouped = $deptFilter.val() === 'all';
        let groupedResources = {};

        if (isGrouped) {
            resources.forEach(res => {
                const deptName = res.department_name || 'Unknown Department';
                if (!groupedResources[deptName]) {
                    groupedResources[deptName] = [];
                }
                groupedResources[deptName].push(res);
            });
        } else {
            const deptName = $("#filter-department option:selected").text();
            groupedResources[deptName] = resources;
        }

        let html = '';

        Object.keys(groupedResources).forEach(deptName => {
            const deptParams = groupedResources[deptName];
            
            html += `
                <div class="department-section mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <h3 class="font-semibold text-gray-900">${deptName}</h3>
                        <span class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">${deptParams.length} resource(s)</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            `;

            deptParams.forEach(res => {
                const statusClass = res.status === 'active' || res.status == 1 ? 'bg-green-50 text-green-700 border-green-300' : 'bg-red-50 text-red-700 border-red-300';
                const statusLabel = res.status === 'active' || res.status == 1 ? 'Active' : 'Inactive';
                
                html += `
                    <div class="resource-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center shrink-0">
                                    <span class="text-xs font-bold text-gray-500">${(res.type || 'RES').substring(0,3).toUpperCase()}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-900 truncate">${res.name}</h4>
                                    <p class="text-xs text-gray-600">${res.type || 'Other'}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status</span>
                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold ${statusClass}">${statusLabel}</span>
                        </div>
                         <div class="flex items-center justify-between text-sm mt-2">
                            <span class="text-gray-600">Capacity</span>
                            <span class="font-medium text-gray-900">${res.capacity}%</span>
                        </div>
                    </div>
                `;
            });
            
            html += `</div></div>`;
        });
        
        $container.html(html);
        $visibleCount.text(resources.length);
    }

    // Attach Listeners
    $branchFilter.on('change', function() {
        const branchId = $(this).val();
        loadDepartments(branchId);
    });

    $deptFilter.on('change', function() {
        fetchAndRenderResources();
    });
    
    $searchInput.on('input', function() {
       // Client side filtering of *currently rendered* resources
       const query = $(this).val().toLowerCase();
       $('#resources-container .resource-card').each(function() {
           const text = $(this).text().toLowerCase();
           $(this).toggle(text.includes(query));
       });
    });

    // Modal Logic
    $('#btn-open-add-resource').on('click', function() {
        $('#modal-add-resource').removeClass('hidden');
        // Pre-select Branch in modal if selected in filter
        const currentBranch = $branchFilter.val();
        if(currentBranch) {
            $('#modal-branch-select').val(currentBranch).change();
        }
    });

    // Modal Branch Change
    $('#modal-branch-select').on('change', function() {
        loadDepartments($(this).val(), '#modal-dept-select');
    });

    // Form Submission
    $('#form-add-resource').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            branch_id: $('#modal-branch-select').val(),
            pln_department_id: $('#modal-dept-select').val(),
            name: $('[name="name"]').val(),
            type: $('[name="type"]').val(),
            capacity: $('[name="capacity"]').val() || 0,
            status: 1
        };
        
        $.ajax({
            url: "{{ route('advancedPlanner.storeResource') }}", 
            method: 'POST',
            data: formData,
            success: function(response) {
                toastr.success('Resource added successfully!');
                $('#modal-add-resource').addClass('hidden');
                $('#form-add-resource')[0].reset();
                fetchAndRenderResources();
            },
            error: function(xhr) {
                toastr.error('Failed to add resource: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });

    // Initial Load - Force fetch to ensure data matches filters (especially branch-specific resources)
    fetchAndRenderResources();
});
</script>