@extends('layouts.app')
@section('title', 'User Management')

@section('content')
    <div class="min-h-screen bg-gray-50/50 py-8">
        <div class=" mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Team Members</h1>
                    <p class="mt-1 text-sm text-gray-500">Manage your team's access, roles, and statuses.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative hidden sm:block">
                        <input type="text" placeholder="Search users..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all shadow-sm">
                        <i class="bi bi-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                    </div>

                    <button onclick="openModal()" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm shadow-indigo-200 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:-translate-y-0.5">
                        <i class="bi bi-plus-lg mr-2"></i>
                        Add Member
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Users</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $users->total() }}</p>
                    </div>
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                        <i class="bi bi-people-fill text-xl"></i>
                    </div>
                </div>
                </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User Details</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                            <tr class="group hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-2 ring-white">
                                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $user->user_name }}</div> </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="bi bi-shield-lock text-gray-400 mr-2 text-xs"></i>
                                        <span class="text-sm text-gray-700 font-medium">{{ $user->user_role_name ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 flex items-center">
                                        <i class="bi bi-telephone-fill text-md text-green-600 font-bold mr-2 opacity-70"></i>
                                        {{ $user->contact_no }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="" class="sr-only peer" onchange="toggleUserStatus({{ $user->id }}, this.checked)" {{ $user->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-700 status-text-{{ $user->id }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                    </label>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button class="p-1.5 rounded-full text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Edit" onclick="editUser({{ $user->id }})">
                                            <i class="bi bi-pencil-square text-lg"></i>
                                        </button>
                                        <button class="p-1.5 rounded-full text-gray-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Assignments" onclick="openAssignmentModal({{ $user->id }})">
                                            <i class="bi bi-diagram-2 text-lg"></i>
                                        </button>
                                        <!-- <button class="p-1.5 rounded-full text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete" onclick="deleteUser({{ $user->id }})">
                                            <i class="bi bi-trash text-lg"></i>
                                        </button> -->
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                            <i class="bi bi-people text-gray-400 text-xl"></i>
                                        </div>
                                        <h3 class="text-gray-900 font-medium text-sm">No users found</h3>
                                        <p class="text-gray-500 text-xs mt-1">Get started by adding a new team member.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div id="createUserModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        
        <div class="flex min-h-screen items-center justify-center p-4 text-center">
            
            <div class="relative w-full max-w-lg transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all border border-gray-100">
                
                {{-- Modal Header --}}
                <div class="bg-gray-50 px-4 py-4 sm:px-6 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100">
                            <i class="bi bi-person-plus text-indigo-600 text-sm"></i>
                        </div>
                        <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Create New User</h3>
                    </div>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-4 py-5 sm:p-6">
                    <p class="text-sm text-gray-500 mb-6" id="modal-description">Enter the user's details below. A temporary password (12345678) will be assigned.</p>
                    
                    <form id="createUserForm" class="space-y-5">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-2" placeholder="John" required>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-2" placeholder="Doe" required>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" id="email" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-2" placeholder="john@example.com" required>
                            </div>
                        </div>

                        <div>
                            <label for="contact_no" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="bi bi-phone text-gray-400"></i>
                                </div>
                                <input type="text" name="contact_no" id="contact_no" class="block w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-2" placeholder="076-1234***" required>
                            </div>
                        </div>

                        <div>
                            <label for="user_role_id" class="block text-sm font-medium text-gray-700 mb-1">Role Assignment</label>
                            <select name="user_role_id" id="user_role_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2.5 px-2" required>
                                <option value="">Select a Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->user_role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div id="formErrors" class="mt-4 hidden">
                        <div class="rounded-lg bg-red-50 border border-red-100 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-x-circle-fill text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1" id="errorList"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="button" id="submitBtn" class="w-full inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                        Create Account
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Right Side Assignment Modal --}}
<div id="assignmentModal" class="relative z-50 hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeAssignmentModal()"></div>
    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                <div class="pointer-events-auto w-screen max-w-md transform transition ease-in-out duration-500 sm:duration-700 translate-x-full" id="assignmentSlideOver">
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        <div class="px-4 py-6 sm:px-6 bg-indigo-700">
                            <div class="flex items-start justify-between">
                                <h2 class="text-lg font-medium text-white" id="slide-over-title">Manage Assignments</h2>
                                <div class="ml-3 flex h-7 items-center">
                                    <button type="button" class="rounded-md bg-indigo-700 text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white" onclick="closeAssignmentModal()">
                                        <span class="sr-only">Close panel</span>
                                        <i class="bi bi-x-lg text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-indigo-200" id="assignmentUserDisplay">Loading user...</p>
                        </div>
                        
                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex" aria-label="Tabs">
                                <button onclick="switchTab('branches')" id="tab-branches" class="w-1/2 border-b-2 border-indigo-500 py-4 px-1 text-center text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:border-indigo-300">
                                    <i class="bi bi-shop mr-2"></i>Branches
                                </button>
                                <button onclick="switchTab('departments')" id="tab-departments" class="w-1/2 border-b-2 border-transparent py-4 px-1 text-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                    <i class="bi bi-grid mr-2"></i>Departments
                                </button>
                            </nav>
                        </div>

                        <div class="relative flex-1 px-4 py-6 sm:px-6" id="assignmentContent">
                            <div id="loadingAssignments" class="flex justify-center items-center h-40 hidden">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-700"></div>
                            </div>
                            
                            <form id="assignmentForm">
                                <input type="hidden" id="assign_user_id" name="user_id">
                                
                                <div id="branches-content" class="space-y-4">
                                    <p class="text-sm text-gray-500 mb-4">Select the branches this user can access.</p>
                                    <div class="space-y-2" id="branchList">
                                        <!-- Branches will be loaded here -->
                                    </div>
                                </div>

                                <div id="departments-content" class="space-y-4 hidden">
                                    <p class="text-sm text-gray-500 mb-4">Select the departments this user belongs to.</p>
                                    <div class="space-y-2" id="departmentList">
                                        <!-- Departments will be loaded here -->
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="flex flex-shrink-0 justify-end px-4 py-4 bg-gray-50 border-t border-gray-100">
                            <button type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" onclick="closeAssignmentModal()">Cancel</button>
                            <button type="button" id="saveAssignmentsBtn" class="ml-4 inline-flex justify-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Save Changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        function openModal(mode = 'create') {
            document.getElementById('createUserModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            if (mode === 'create') {
                $('#createUserForm')[0].reset();
                $('#user_id').val('');
                $('#modal-title').text('Create New User');
                $('#submitBtn').text('Create Account');
                $('#modal-description').text("Enter the user's details below. A temporary password (12345678) will be assigned.");
                $('#formErrors').addClass('hidden');
            }
        }

        function closeModal() {
            document.getElementById('createUserModal').classList.add('hidden');
            document.body.style.overflow = 'auto'; 
            $('#createUserForm')[0].reset();
            $('#formErrors').addClass('hidden');
        }

        $(document).ready(function() {
            // Configure Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            $('#submitBtn').click(function(e) {
                e.preventDefault();
                
                $('#formErrors').addClass('hidden');
                $('#errorList').empty();
                
                var $btn = $(this);
                var userId = $('#user_id').val();
                var loadingText = userId ? 'Updating...' : 'Creating...';
                
                $btn.prop('disabled', true).html('<div class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div> ' + loadingText);

                var url = userId ? "{{ route('userManagement.index') }}/" + userId + "/update" : "{{ route('userManagement.store') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: $('#createUserForm').serialize(),
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message || 'Operation successful!');
                            setTimeout(function() {
                                location.reload();
                            }, 1000); 
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).text(userId ? 'Update Account' : 'Create Account');
                        
                        var errors = xhr.responseJSON.errors || {};
                        var message = xhr.responseJSON.message || 'An error occurred.';
                        
                        toastr.error('Please resolve the errors below.');

                        if (Object.keys(errors).length > 0) {
                             $('#formErrors').removeClass('hidden');
                             $.each(errors, function(key, value) {
                                 $('#errorList').append('<li>' + value[0] + '</li>');
                             });
                        } else {
                            toastr.error(message);
                        }
                    }
                });
            });

            window.editUser = function(id) {
                $('#user_id').val(id);
                $('#modal-title').text('Edit User');
                $('#submitBtn').text('Update Account');
                $('#modal-description').text("Update the user's details below.");
                $('#formErrors').addClass('hidden');
                
                $.ajax({
                    url: "{{ route('userManagement.index') }}/" + id + "/edit",
                    type: "GET",
                    success: function(response) {
                        if(response.success) {
                            var user = response.data;
                            $('#first_name').val(user.first_name);
                            $('#last_name').val(user.last_name);
                            $('#email').val(user.user_name);
                            $('#contact_no').val(user.contact_no);
                            $('#user_role_id').val(user.user_role_id);
                            
                            openModal('edit');
                        } else {
                            toastr.error('Could not fetch user data.');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to load user data.');
                    }
                });
            };
            
            window.deleteUser = function(id) {
                if(confirm('Are you sure you want to delete this user?')) {
                     toastr.info('Delete functionality coming soon!');
                }
            }

            window.toggleUserStatus = function(id, status) {
                var newStatus = status ? 1 : 0;
                $.ajax({
                    url: "{{ route('userManagement.toggleStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id,
                        is_active: newStatus
                    },
                    success: function(response) {
                        if(response.success) {
                            toastr.success(response.message);
                            $('.status-text-' + id).text(newStatus ? 'Active' : 'Inactive');
                        } else {
                            toastr.error('Failed to update status.');
                            // Revert toggle if failed
                            $('input[type="checkbox"][onchange="toggleUserStatus('+id+', this.checked)"]').prop('checked', !status);
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred while updating status.');
                        // Revert toggle if error
                        $('input[type="checkbox"][onchange="toggleUserStatus('+id+', this.checked)"]').prop('checked', !status);
                    }
                });
            }
        });
        
        // --- Assignment Modal Logic ---
        var assignmentUserId = null;

        window.openAssignmentModal = function(userId) {
            assignmentUserId = userId;
            document.getElementById('assignmentModal').classList.remove('hidden');
            // Animation
            setTimeout(() => {
                document.getElementById('assignmentSlideOver').classList.remove('translate-x-full');
            }, 10);
            document.body.style.overflow = 'hidden';
            
            $('#assign_user_id').val(userId);
            $('#assignmentUserDisplay').text('Loading details...');
            $('#branchList').empty();
            $('#departmentList').empty();
            $('#loadingAssignments').removeClass('hidden');
            $('#branches-content').addClass('hidden');
            $('#departments-content').addClass('hidden');
            
            // Reset Tabs
            $('#tab-branches').addClass('border-indigo-500 text-indigo-600').removeClass('border-transparent text-gray-500');
            $('#tab-departments').removeClass('border-indigo-500 text-indigo-600').addClass('border-transparent text-gray-500');

            $.ajax({
                url: "{{ route('userManagement.index') }}/" + userId + "/assignments",
                type: "GET",
                success: function(response) {
                    if(response.success) {
                        $('#loadingAssignments').addClass('hidden');
                        $('#branches-content').removeClass('hidden'); 
                        
                        // Populate Branches
                        if(response.branches && response.branches.length > 0) {
                            response.branches.forEach(branch => {
                                const isChecked = response.assigned_branches.includes(branch.id) ? 'checked' : '';
                                $('#branchList').append(`
                                    <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="branches[]" value="${branch.id}" ${isChecked} class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-900">${branch.name}</span>
                                    </label>
                                `);
                            });
                        } else {
                            $('#branchList').html('<p class="text-sm text-gray-400 italic">No active branches found.</p>');
                        }

                        // Populate Departments
                        if(response.departments && response.departments.length > 0) {
                            response.departments.forEach(dept => {
                                const isChecked = response.assigned_departments.includes(dept.id) ? 'checked' : '';
                                $('#departmentList').append(`
                                    <label class="flex items-center space-x-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors">
                                        <input type="checkbox" name="departments[]" value="${dept.id}" ${isChecked} class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                        <span class="text-sm font-medium text-gray-900">${dept.name}</span>
                                    </label>
                                `);
                            });
                        } else {
                            $('#departmentList').html('<p class="text-sm text-gray-400 italic">No active departments found.</p>');
                        }
                        
                        $('#assignmentUserDisplay').text('Managing assignments for selected user');
                    } else {
                        toastr.error('Failed to load assignments.');
                        closeAssignmentModal();
                    }
                },
                error: function() {
                    toastr.error('Error fetching data.');
                    closeAssignmentModal();
                }
            });
        };

        window.closeAssignmentModal = function() {
            document.getElementById('assignmentSlideOver').classList.add('translate-x-full');
            setTimeout(() => {
                document.getElementById('assignmentModal').classList.add('hidden');
            }, 300);
            document.body.style.overflow = 'auto';
        };

        window.switchTab = function(tab) {
            if (tab === 'branches') {
                $('#branches-content').removeClass('hidden');
                $('#departments-content').addClass('hidden');
                $('#tab-branches').addClass('border-indigo-500 text-indigo-600').removeClass('border-transparent text-gray-500');
                $('#tab-departments').removeClass('border-indigo-500 text-indigo-600').addClass('border-transparent text-gray-500');
            } else {
                $('#departments-content').removeClass('hidden');
                $('#branches-content').addClass('hidden');
                $('#tab-departments').addClass('border-indigo-500 text-indigo-600').removeClass('border-transparent text-gray-500');
                $('#tab-branches').removeClass('border-indigo-500 text-indigo-600').addClass('border-transparent text-gray-500');
            }
        };

        $(document).ready(function() {
             $('#saveAssignmentsBtn').click(function() {
                var userId = $('#assign_user_id').val();
                var $btn = $(this);
                var originalText = $btn.text();
                
                $btn.prop('disabled', true).html('<div class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin mr-2"></div> Saving...');
                
                // Get selected branches and departments
                var selectedBranches = [];
                $('input[name="branches[]"]:checked').each(function() {
                    selectedBranches.push($(this).val());
                });

                var selectedDepartments = [];
                $('input[name="departments[]"]:checked').each(function() {
                    selectedDepartments.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('userManagement.index') }}/" + userId + "/assignments",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        branches: selectedBranches,
                        departments: selectedDepartments
                    },
                    success: function(response) {
                        $btn.prop('disabled', false).text(originalText);
                        if(response.success) {
                            toastr.success('Assignments updated successfully!');
                            closeAssignmentModal();
                        } else {
                             toastr.error(response.message || 'Failed to update.');
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).text(originalText);
                        var msg = xhr.responseJSON.message || 'An error occurred';
                        toastr.error(msg);
                    }
                });
            });
        });
    </script>
@endsection