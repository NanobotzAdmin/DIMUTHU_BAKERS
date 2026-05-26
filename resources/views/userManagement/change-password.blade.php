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
                            <a href="{{ route('profile.index') }}" class="hover:text-amber-500 transition-colors">
                                Your Profile
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="bi bi-chevron-right text-[10px] mx-1 text-gray-400"></i>
                            <span class="text-gray-400">Security & Password</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Security & Password</h1>
            <p class="text-sm text-slate-500 mt-1">Change your account password securely by validating your current password.</p>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Security Tips Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                <!-- Decorative Profile Banner -->
                <div class="h-32 bg-gradient-to-r from-slate-700 via-slate-800 to-slate-900 relative flex items-center justify-center">
                    <span class="text-amber-400 text-4xl"><i class="bi bi-shield-lock-fill"></i></span>
                </div>

                <!-- Card Content -->
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-bold text-slate-800">Password Requirements</h3>
                    <ul class="space-y-3 text-xs text-slate-500 font-medium">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500"><i class="bi bi-patch-check-fill"></i></span>
                            <span>Must be at least 8 characters long</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500"><i class="bi bi-patch-check-fill"></i></span>
                            <span>Should contain uppercase and lowercase characters</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-500"><i class="bi bi-patch-check-fill"></i></span>
                            <span>Should include numbers or special characters</span>
                        </li>
                    </ul>
                </div>

                <!-- Additional Security Notice -->
                <div class="border-t border-slate-100 px-6 py-6 bg-slate-50/50">
                    <p class="text-xs text-slate-400 leading-relaxed">
                        <i class="bi bi-info-circle-fill text-amber-500 mr-1"></i> 
                        For your security, once your password is successfully changed, you will remain logged in, but any other active browser sessions will be refreshed.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Column: Settings Form -->
        <div class="lg:col-span-2">
            <!-- Navigation Header (Clean & Premium) -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50/50 px-8 py-5">
                    <h3 class="text-lg font-bold text-slate-800"><i class="bi bi-key-fill text-amber-500 mr-2"></i>Change Password</h3>
                </div>

                <!-- Form Container -->
                <form id="password-update-form" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Current Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="current_password" id="current_password" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" placeholder="Enter your current password" required>
                            </div>
                        </div>

                        <hr class="border-slate-100">

                        <!-- New Password & Confirm New Password -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">New Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" name="password" id="password" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" placeholder="Minimum 8 characters" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Confirm New Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="block w-full pl-11 pr-4 py-3 bg-slate-50 hover:bg-slate-100/50 border border-slate-200 focus:border-amber-500 focus:ring-2 focus:ring-amber-200/40 rounded-2xl transition-all duration-200 text-sm font-medium text-slate-800" placeholder="Repeat new password" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Submit Actions -->
                    <div class="border-t border-slate-100 mt-8 pt-8 flex items-center justify-end gap-4">
                        <a href="{{ route('profile.index') }}" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-xs transition-all duration-200 flex items-center justify-center">
                            Cancel
                        </a>
                        <button type="submit" id="save-password-btn" class="px-8 py-3.5 bg-amber-500 text-white rounded-2xl font-bold text-xs shadow-lg shadow-amber-200 hover:bg-amber-600 hover:shadow-amber-300 transition-all duration-250 flex items-center gap-2">
                            <span id="btn-text">Update Password</span>
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
        $('#password-update-form').on('submit', function(e) {
            e.preventDefault();

            // Password match check
            const password = $('#password').val();
            const passwordConfirm = $('#password_confirmation').val();

            if (password && password !== passwordConfirm) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Error!',
                        text: 'New passwords do not match. Please verify.',
                        icon: 'error',
                        confirmButtonColor: '#D4A017'
                    });
                } else {
                    alert('New passwords do not match!');
                }
                return;
            }

            // Spinner & Button disabled
            $('#save-password-btn').prop('disabled', true);
            $('#btn-text').text('Updating...');
            $('#btn-spinner').removeClass('hidden');

            $.ajax({
                url: "{{ route('password.change.update') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $('#save-password-btn').prop('disabled', false);
                    $('#btn-text').text('Update Password');
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
                                // Clear fields
                                $('#current_password').val('');
                                $('#password').val('');
                                $('#password_confirmation').val('');
                            });
                        } else {
                            alert(response.message);
                            $('#current_password').val('');
                            $('#password').val('');
                            $('#password_confirmation').val('');
                        }
                    } else {
                        showError(response.message || 'Error updating password.');
                    }
                },
                error: function(xhr) {
                    $('#save-password-btn').prop('disabled', false);
                    $('#btn-text').text('Update Password');
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
