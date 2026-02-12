{{--
resources/views/settings/users.blade.php

Expected variables from Controller:
$users - Collection of User models
$roles - Collection of Role models (or array with permissions)
--}}

@php
    // Data passed from controller: $users, $roles
@endphp

<div class="space-y-6">
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Users Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Users & Permissions</h2>
                <p class="text-sm text-gray-600">Manage user accounts and access roles</p>
            </div>
        </div>

        <button onclick="toggleAddUserForm(true)"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            {{-- Plus Icon --}}
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add User
        </button>
    </div>

    {{-- Add User Form (Hidden by default) --}}
    <div id="add-user-form" class="hidden bg-white p-6 rounded-lg border-2 border-[#D4A017] shadow-sm">
        <form id="user-form" onsubmit="event.preventDefault(); saveUser();" action="{{ route('userManagement.store') }}"
            method="POST">
            @csrf
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Add New User</h3>
                <button type="button" onclick="toggleAddUserForm(false)" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                    <input type="text" name="first_name" id="first_name" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="John">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="Doe">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" required
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="john@yourbakery.com">
                </div>

                <div>
                    <label for="contact_no" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="contact_no" id="contact_no"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="+94 77 123 4567">
                </div>

                <div class="md:col-span-2">
                    <label for="user_role_id" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="user_role_id" id="user_role_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->user_role_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100">
                <button type="button" onclick="toggleAddUserForm(false)"
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
                    Add User
                </button>
            </div>
        </form>
    </div>

    {{-- User List --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Location</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                            Login</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-[#D4A017]/10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-medium text-[#D4A017]">
                                            {{ Str::upper(Str::substr($user->first_name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $user->first_name }}
                                        {{ $user->last_name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm">
                                    <div class="text-gray-900">{{ $user->user_name }}</div>
                                    <div class="text-gray-500">{{ $user->contact_no }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    // Find role name from the passed query collection
                                    $userRole = $roles->firstWhere('id', $user->user_role_id);
                                    $roleName = $userRole ? $userRole->user_role_name : 'Unknown';

                                    // Simple color logic based on role name content
                                    $badgeColor = match (true) {
                                        Str::contains(Str::lower($roleName), 'admin') => 'bg-purple-100 text-purple-800',
                                        Str::contains(Str::lower($roleName), 'manager') => 'bg-blue-100 text-blue-800',
                                        Str::contains(Str::lower($roleName), 'store') => 'bg-green-100 text-green-800',
                                        Str::contains(Str::lower($roleName), 'production') => 'bg-orange-100 text-orange-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                    {{ $roleName }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-sm text-gray-600">
                                    {{ $user->branches->pluck('name')->join(', ') ?: 'None' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->is_active)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{-- Last Login not available in UmUser, using Updated At as placeholder or N/A --}}
                                {{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('M d, H:i') : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Manage Assignments Button --}}
                                    <button type="button"
                                        onclick='openAdminAssignmentsModal({{ $user->id }}, @json($user->first_name))'
                                        class="p-1 rounded-md hover:bg-gray-100 text-blue-600" title="Manage Assignments">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                            </path>
                                        </svg>
                                    </button>

                                    {{-- Edit Button --}}
                                    <button type="button" onclick="editUser({{ $user->id }})"
                                        class="p-1 rounded-md hover:bg-gray-100 text-yellow-600" title="Edit User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    {{-- Toggle Active Form --}}
                                    {{-- Toggle Active Form --}}
                                    <button onclick="toggleUserStatus({{ $user->id }}, {{ $user->is_active ? 1 : 0 }})"
                                        class="p-1 rounded-md hover:bg-gray-100 {{ $user->is_active ? 'text-red-600' : 'text-green-600' }}"
                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($user->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @endif
                                    </button>

                                    {{-- Delete Form (Backend not implemented yet) --}}
                                    {{--
                                    <form action="" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-md hover:bg-gray-100 text-red-600"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                    --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Role Permissions Reference --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Role Permissions Reference</h3>
            <button onclick="toggleUserRoleForm(true)"
                class="inline-flex items-center px-3 py-1.5 border border-[#D4A017] rounded-md shadow-sm text-sm font-medium text-[#D4A017] bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Role
            </button>
        </div>

        {{-- Add Role Form (Hidden by default) --}}
        <div id="add-role-form" class="hidden mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
            <form id="role-form" onsubmit="event.preventDefault(); saveUserRole();">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="user_role_name" class="block text-sm font-medium text-gray-700 mb-1">Role Name
                            *</label>
                        <input type="text" name="user_role_name" id="user_role_name" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="e.g. Supervisor">
                    </div>
                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" name="description" id="description"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                            placeholder="Role description...">
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleUserRoleForm(false)"
                        class="px-3 py-1.5 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B]">
                        Save Role
                    </button>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($roles as $role)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 {{ $role->id === 'super_admin' ? 'text-purple-600' : 'text-gray-600' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                        <h4 class="font-semibold text-gray-900">{{ $role->user_role_name }}</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">{{ $role->remark1 }}</p>
                    {{-- Permissions display removed as it's not available in PmUserRole model --}}
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Assignments Modal (Slide-Over) --}}
<div id="admin-assignments-modal" class="fixed inset-0 z-[100] hidden" aria-labelledby="slide-over-title" role="dialog"
    aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
        onclick="closeAdminAssignmentsModal()"></div>
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500 sm:duration-700 translate-x-full"
                    id="adminAssignmentSlideOver">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="px-4 py-6 sm:px-6 bg-[#D4A017]">
                            <div class="flex items-start justify-between">
                                <h2 class="text-lg font-medium text-white" id="slide-over-title">Manage Assignments
                                </h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button"
                                        class="rounded-md bg-[#D4A017] text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white"
                                        onclick="closeAdminAssignmentsModal()">
                                        <span class="sr-only">Close panel</span>
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-indigo-100" id="admin-assignment-user-name">Loading user...
                            </p>
                        </div>

                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex" aria-label="Tabs">
                                <button onclick="switchAdminTab('branches')" id="tab-branches"
                                    class="w-1/2 border-b-2 border-[#D4A017] py-4 px-1 text-center text-sm font-medium text-[#D4A017] hover:text-[#B8860B]">
                                    Branches
                                </button>
                                <button onclick="switchAdminTab('departments')" id="tab-departments"
                                    class="w-1/2 border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                    Departments
                                </button>
                            </nav>
                        </div>

                        <div class="relative flex-1 px-4 py-6 sm:px-6">
                            <form id="admin-assignments-form">
                                @csrf
                                <input type="hidden" id="admin-assignment-user-id">

                                <div id="branches-content" class="space-y-4">
                                    <p class="text-sm text-gray-500 mb-4">Select the branches this user can access.
                                    </p>
                                    <div class="space-y-2" id="branches-list">
                                        {{-- Checkboxes populated via JS --}}
                                        <div class="text-sm text-gray-500">Loading...</div>
                                    </div>
                                </div>

                                <div id="departments-content" class="space-y-4 hidden">
                                    <p class="text-sm text-gray-500 mb-4">Select the departments this user belongs
                                        to.</p>
                                    <div class="space-y-2" id="departments-list">
                                        {{-- Checkboxes populated via JS --}}
                                        <div class="text-sm text-gray-500">Loading...</div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="flex flex-shrink-0 justify-end px-4 py-4 bg-gray-50 border-t border-gray-100">
                            <button type="button"
                                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2"
                                onclick="closeAdminAssignmentsModal()">Cancel</button>
                            <button type="button" onclick="saveAdminAssignments()"
                                class="ml-4 inline-flex justify-center rounded-lg border border-transparent bg-[#D4A017] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2">Save
                                Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function saveUser() {
        const form = document.getElementById('user-form');
        const formData = new FormData(form);
        const url = form.action;

        // Clear previous errors
        document.querySelectorAll('.text-red-600').forEach(el => el.remove());
        document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#D4A017'
                    }).then(() => {
                        toggleAddUserForm(false);
                        window.location.reload();
                    });
                } else {
                    if (data.errors) {
                        // Show validation errors
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementById(key);
                            if (input) {
                                input.classList.add('border-red-500');
                                const errorMsg = document.createElement('p');
                                errorMsg.className = 'text-red-600 text-xs mt-1';
                                errorMsg.textContent = data.errors[key][0];
                                input.parentNode.appendChild(errorMsg);
                            }
                        });
                        Swal.fire({
                            title: 'Error!',
                            text: 'Please check the form for errors.',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Something went wrong',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
    }

    function toggleAddUserForm(show) {
        const form = document.getElementById('add-user-form');
        const formEl = form.querySelector('form');
        const titleEl = form.querySelector('h3');
        const submitBtn = form.querySelector('button[type="submit"]');

        if (show) {
            form.classList.remove('hidden');
            // Reset for Add Mode
            if (!formEl.dataset.isEdit) {
                formEl.reset();
                formEl.action = "{{ route('userManagement.store') }}";
                titleEl.textContent = 'Add New User';
                submitBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add User`;
                // Remove hidden PATCH method if exists
                const existingMethod = formEl.querySelector('input[name="_method"]');
                if (existingMethod) existingMethod.remove();
            }
        } else {
            form.classList.add('hidden');
            delete formEl.dataset.isEdit; // Clear edit mode flag
        }
    }

    function editUser(id) {
        // Fetch user details
        fetch(`/user-management/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const user = data.data;
                    const formContainer = document.getElementById('add-user-form');
                    const formEl = formContainer.querySelector('form');

                    toggleAddUserForm(true);

                    // Mark as Edit Mode
                    formEl.dataset.isEdit = 'true';

                    // Update Form Action
                    formEl.action = `/user-management/${id}/update`; // Corrected route

                    // Add PATCH method field
                    let methodInput = formEl.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'POST'; // Controller update method expects POST usually, but let's check web.php. 
                        // web.php: Route::post('/user-management/{id}/update', ...). So POST is correct. No need for PATCH/PUT unless using Resource controller defaults.
                        // Wait, previous code plan said PATCH? Let's check route definition again.
                        // Route::post('/user-management/{id}/update', ...). So it is POST. 
                        // I will NOT add _method field.
                    }

                    // Populate fields
                    document.getElementById('first_name').value = user.first_name;
                    document.getElementById('last_name').value = user.last_name;
                    document.getElementById('email').value = user.user_name; // Mapped email -> user_name
                    document.getElementById('contact_no').value = user.contact_no;
                    document.getElementById('user_role_id').value = user.user_role_id;

                    // Update UI
                    formContainer.querySelector('h3').textContent = 'Edit User';
                    formContainer.querySelector('button[type="submit"]').innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Update User`;
                }
            })
            .catch(error => console.error('Error fetching user:', error));
    }

    // --- Assignments Logic ---

    function openAdminAssignmentsModal(userId, userName) {
        const modal = document.getElementById('admin-assignments-modal');
        document.getElementById('admin-assignment-user-name').textContent = userName;
        document.getElementById('admin-assignment-user-id').value = userId;
        modal.classList.remove('hidden');

        // Slide Animation
        setTimeout(() => {
            document.getElementById('adminAssignmentSlideOver').classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';

        // Reset Tabs
        switchAdminTab('branches');

        // Fetch assignments
        fetch(`/user-management/${userId}/assignments`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate Branches
                    const branchesList = document.getElementById('branches-list');
                    branchesList.innerHTML = '';
                    if (data.branches && data.branches.length > 0) {
                        data.branches.forEach(branch => {
                            const isChecked = data.assigned_branches.includes(branch.id) ? 'checked' : '';
                            branchesList.innerHTML += `
                                <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="branches[]" value="${branch.id}" ${isChecked} class="h-5 w-5 text-[#D4A017] rounded border-gray-300 focus:ring-[#D4A017]">
                                    <span class="text-sm font-medium text-gray-900">${branch.name}</span>
                                </label>
                            `;
                        });
                    } else {
                        branchesList.innerHTML = '<p class="text-sm text-gray-400 italic">No active branches found.</p>';
                    }

                    // Populate Departments
                    const departmentsList = document.getElementById('departments-list');
                    departmentsList.innerHTML = '';
                    if (data.departments && data.departments.length > 0) {
                        data.departments.forEach(dept => {
                            const isChecked = data.assigned_departments.includes(dept.id) ? 'checked' : '';
                            departmentsList.innerHTML += `
                                <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="departments[]" value="${dept.id}" ${isChecked} class="h-5 w-5 text-[#D4A017] rounded border-gray-300 focus:ring-[#D4A017]">
                                    <span class="text-sm font-medium text-gray-900">${dept.name}</span>
                                </label>
                            `;
                        });
                    } else {
                        departmentsList.innerHTML = '<p class="text-sm text-gray-400 italic">No active departments found.</p>';
                    }
                }
            });
    }

    function closeAdminAssignmentsModal() {
        document.getElementById('adminAssignmentSlideOver').classList.add('translate-x-full');
        setTimeout(() => {
            document.getElementById('admin-assignments-modal').classList.add('hidden');
        }, 300);
        document.body.style.overflow = 'auto';
    }

    function switchAdminTab(tab) {
        if (tab === 'branches') {
            document.getElementById('branches-content').classList.remove('hidden');
            document.getElementById('departments-content').classList.add('hidden');

            const tabEl = document.getElementById('tab-branches');
            tabEl.classList.add('border-[#D4A017]', 'text-[#D4A017]');
            tabEl.classList.remove('border-transparent', 'text-gray-500');

            const otherTab = document.getElementById('tab-departments');
            otherTab.classList.remove('border-[#D4A017]', 'text-[#D4A017]');
            otherTab.classList.add('border-transparent', 'text-gray-500');
        } else {
            document.getElementById('departments-content').classList.remove('hidden');
            document.getElementById('branches-content').classList.add('hidden');

            const tabEl = document.getElementById('tab-departments');
            tabEl.classList.add('border-[#D4A017]', 'text-[#D4A017]');
            tabEl.classList.remove('border-transparent', 'text-gray-500');

            const otherTab = document.getElementById('tab-branches');
            otherTab.classList.remove('border-[#D4A017]', 'text-[#D4A017]');
            otherTab.classList.add('border-transparent', 'text-gray-500');
        }
    }

    function saveAdminAssignments() {
        const userId = document.getElementById('admin-assignment-user-id').value;
        const form = document.getElementById('admin-assignments-form');
        const formData = new FormData(form);

        fetch(`/user-management/${userId}/assignments`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Assignments updated successfully',
                        icon: 'success',
                        confirmButtonColor: '#D4A017'
                    }).then(() => {
                        closeAdminAssignmentsModal();
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Something went wrong',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
    }

    function toggleUserStatus(userId, currentStatus) {
        const newStatus = currentStatus ? 0 : 1;
        const action = newStatus ? 'Activate' : 'Deactivate';
        const actionText = newStatus ? 'This user will be able to access the system.' : 'This user will lose access to the system.';

        Swal.fire({
            title: `Are you sure you want to ${action} this user?`,
            text: actionText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: newStatus ? '#10B981' : '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: `Yes, ${action} him!`
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('userManagement.toggleStatus') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: userId,
                        is_active: newStatus
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Updated!',
                                text: `User has been ${action}d.`, // Activated or Deactivated
                                icon: 'success',
                                confirmButtonColor: '#D4A017'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    });
            }
        });

    }

    // --- User Role Logic ---

    function toggleUserRoleForm(show) {
        const form = document.getElementById('add-role-form');
        if (show) {
            form.classList.remove('hidden');
            document.getElementById('role-form').reset();
        } else {
            form.classList.add('hidden');
        }
    }

    function saveUserRole() {
        const form = document.getElementById('role-form');
        const formData = new FormData(form);

        fetch("{{ route('userRoles.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Role added successfully',
                        icon: 'success',
                        confirmButtonColor: '#D4A017'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error!', data.message || 'Failed to add role', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Something went wrong', 'error');
            });
    }

</script>