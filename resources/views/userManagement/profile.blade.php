@extends('layouts.app')

@section('content')
<div class="py-8 max-w-7xl mx-auto">
    <!-- Breadcrumbs & Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 text-xs font-medium text-gray-400">
                    <li class="inline-flex items-center">
                        <a href="{{ url('/adminDashboard') }}" class="hover:text-amber-500 transition-colors">
                            <i class="bi bi-house-door-fill mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-[10px] mx-1 text-gray-400"></i>
                            <span class="text-gray-400">Your Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Profile Settings</h1>
            <p class="text-sm text-slate-500 mt-1">Update your personal details, email address, and contact number.</p>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <!-- Decorative Profile Banner (Warm Bakery Gold to Bronze Gradient) -->
                <div class="h-32 bg-gradient-to-r from-amber-500 via-yellow-600 to-amber-700 relative">
                    <div class="absolute -bottom-12 left-1/2 -translate-x-1/2">
                        <div class="h-24 w-24 rounded-full border-4 border-white bg-gradient-to-br from-amber-500 to-yellow-600 text-white flex items-center justify-center text-3xl font-extrabold shadow-xl">
                            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'U', 0, 1)) }}
                        </div>
                    </div>
                </div>

                <!-- Profile Info Header -->
                <div class="pt-16 pb-6 px-6 text-center">
                    <h2 class="text-2xl font-bold text-slate-900">
                        {{ $user->first_name ?? 'User' }} {{ $user->last_name ?? '' }}
                    </h2>
                    
                    <!-- Username (Read Only Badge) -->
                    <div class="mt-2 flex items-center justify-center gap-1.5">
                        <span class="text-xs text-slate-400 font-medium">Username:</span>
                        <span class="text-xs font-mono font-bold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">
                            {{ $user->user_name ?? 'N/A' }}
                        </span>
                    </div>
                    
                    <!-- Role Pill -->
                    <span class="inline-flex items-center mt-3.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                        <i class="bi bi-shield-lock-fill mr-1"></i> {{ $user->userRole?->user_role_name ?? 'Staff' }}
                    </span>
                </div>

                <!-- Profile Summary Quick Info -->
                <div class="border-t border-slate-100 px-6 py-6 space-y-4 bg-slate-50/50">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Branch Access</span>
                        <span class="text-amber-700 font-bold bg-amber-50 px-2.5 py-0.5 rounded-md text-xs">
                            {{ $user->currentBranch?->name ?? 'Not Set' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">Account Status</span>
                        <span class="inline-flex items-center text-xs font-semibold {{ $user->is_active ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2.5 py-0.5 rounded-md">
                            <span class="h-1.5 w-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }} mr-1.5"></span>
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500 font-medium">User ID</span>
                        <span class="text-slate-700 font-mono">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('password.change') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-slate-900 text-white hover:bg-amber-500 hover:text-white rounded-2xl text-xs font-bold transition-all duration-250 shadow-md shadow-slate-100 hover:shadow-amber-100">
                            <i class="bi bi-key-fill text-amber-400"></i> Change Password
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Form -->
        <div class="lg:col-span-2">
            <!-- Navigation Header (Clean & Premium) -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                    <h3 class="text-lg font-bold text-slate-800"><i class="bi bi-person-fill text-amber-500 mr-2"></i>Personal Profile Details</h3>
                </div>

                <!-- Form Container -->
                <form id="profile-update-form" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">First Name</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Last Name</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" required>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Email Address</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="contact_no" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Contact Number</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no', $user->contact_no) }}" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Submit Actions -->
                    <div class="border-t border-slate-100 mt-8 pt-8 flex items-center justify-end gap-4">
                        <button type="submit" id="save-profile-btn" class="px-8 py-3.5 bg-amber-500 text-white rounded-2xl font-bold text-xs shadow-lg shadow-amber-200 hover:bg-amber-600 hover:shadow-amber-300 transition-all duration-250 flex items-center gap-2">
                            <span id="btn-text">Save Changes</span>
                            <span id="btn-spinner" class="hidden h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $('#profile-update-form').on('submit', function(e) {
            e.preventDefault();

            // Spinner & Button disabled
            $('#save-profile-btn').prop('disabled', true);
            $('#btn-text').text('Saving...');
            $('#btn-spinner').removeClass('hidden');

            $.ajax({
                url: "{{ route('profile.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#save-profile-btn').prop('disabled', false);
                    $('#btn-text').text('Save Changes');
                    $('#btn-spinner').addClass('hidden');

                    if (response.success) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            alert(response.message);
                            window.location.reload();
                        }
                    } else {
                        showError(response.message || 'Error updating profile.');
                    }
                },
                error: function(xhr) {
                    $('#save-profile-btn').prop('disabled', false);
                    $('#btn-text').text('Save Changes');
                    $('#btn-spinner').addClass('hidden');

                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showError(errorMsg);
                }
            });
        });

        function showError(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonColor: '#D4A017'
                });
            } else {
                alert(message);
            }
        }
    });
</script>
@endsection
