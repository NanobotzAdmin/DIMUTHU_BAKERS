@extends('layouts.app')
@section('title', 'User Configuration')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">User Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage user roles and permissions.</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('user-roles')" id="tab-btn-user-roles"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-indigo-500 text-indigo-600">
                User Roles
            </button>
        </nav>
    </div>

    {{-- Tab Content: User Roles --}}
    <div id="tab-content-user-roles" class="space-y-4">
        <div class="flex justify-end">
            <button onclick="openUserRoleModal('add')" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Add User Role
            </button>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="userRolesTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modals --}}
<!-- User Role Modal -->
<div id="userRoleModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('userRoleModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="userRoleModalTitle">Add User Role</h3>
                <div class="mt-4">
                    <input type="hidden" id="userRoleId">
                    <label for="userRoleName" class="block text-sm font-medium text-gray-700">Role Name</label>
                    <input type="text" id="userRoleName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2 mb-3">

                    <label for="userRoleDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="userRoleDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2"></textarea>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveUserRole()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                <button type="button" onclick="closeModal('userRoleModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // --- User Roles Logic ---
    function loadUserRoles() {
        $.get('{{ route("userRoles.fetch") }}', function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No user roles found.</td></tr>';
            } else {
                data.forEach(item => {
                    html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.user_role_name}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">${item.remark1 || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button onclick="openUserRoleModal('edit', ${item.id}, '${item.user_role_name}', '${(item.remark1 || '').replace(/'/g, "\\'")}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button onclick="deleteUserRole(${item.id})" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            }
            $('#userRolesTableBody').html(html);
        });
    }

    function openUserRoleModal(mode, id = null, name = '', description = '') {
        $('#userRoleModalTitle').text(mode === 'add' ? 'Add User Role' : 'Edit User Role');
        $('#userRoleId').val(id);
        $('#userRoleName').val(name);
        $('#userRoleDescription').val(description);
        $('#userRoleModal').removeClass('hidden');
    }

    function saveUserRole() {
        const id = $('#userRoleId').val();
        const name = $('#userRoleName').val();
        const description = $('#userRoleDescription').val();
        const url = id ? '{{ route("userRoles.update") }}' : '{{ route("userRoles.store") }}';

        $.post(url, {
            id: id,
            user_role_name: name,
            description: description
        }, function(response) {
            closeModal('userRoleModal');
            loadUserRoles();
            toastr.success('User Role saved successfully');
        }).fail(function(xhr) {
            let errorMsg = 'Error saving user role';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            toastr.error(errorMsg);
        });
    }

    function deleteUserRole(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("userRoles.delete") }}',
                type: 'DELETE',
                data: {
                    id: id
                },
                success: function(response) {
                    loadUserRoles();
                    Swal.fire(
                        'Deleted!',
                        'User Role deleted successfully',
                        'success'
                    );
                },
                error: function(xhr) {
                    let errorMsg = 'Error deleting user role';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire(
                        'Error!',
                        errorMsg,
                        'error'
                    );
                }
            });
        }
    }); // <-- closes Swal.then
} // <-- closes deleteUserRole function


    function closeModal(id) {
        $('#' + id).addClass('hidden');
    }

    // --- Tab Switching Logic ---
    function switchTab(tabName) {
        // Since we only have one tab now, this is simpler, but keeping structure for future extensibility
        $('#tab-content-user-roles').removeClass('hidden');

        // Load data
        if (tabName === 'user-roles') loadUserRoles();
    }

    // Initial load based on default active tab
    $(document).ready(function() {
        switchTab('user-roles');
    });
</script>
@endsection