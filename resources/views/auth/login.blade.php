<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BakeryMate ERP</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        
        /* Custom Shake Animation for Errors */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .animate-shake { animation: shake 0.4s cubic-bezier(.36,.07,.19,.97) both; }

        /* Smooth Fade In */
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-orange-100 min-h-screen flex items-center justify-center p-4">

    <div class="fade-in-up bg-white w-full max-w-5xl h-auto md:h-[650px] rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row">
        
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
            
            <div class="mb-10 text-center md:text-left">
                <img src="{{ asset('images/logo.png') }}" alt="BakeryMate Logo" class="h-24 w-auto inline-block mb-2">
                <h2 class="text-2xl font-bold text-slate-800">Welcome Back!</h2>
                <p class="text-slate-500 text-sm mt-1">Please enter your details to sign in.</p>
            </div>

            <div id="errorMessage" class="hidden transform transition-all duration-300 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm mb-6 flex items-start gap-3">
                <i class="bi bi-exclamation-circle-fill text-lg"></i>
                <span class="text-sm font-medium message-text"></span>
            </div>

            <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-6">
                @csrf
                
                <div class="relative group">
                    <input type="text" name="email" id="email" required 
                        class="peer w-full h-12 bg-transparent border-b-2 border-slate-200 text-slate-900 placeholder-transparent focus:outline-none focus:border-orange-500 transition-colors duration-300 pl-8"
                        placeholder="Username" />
                    
                    <label for="email" 
                        class="absolute left-8 -top-3.5 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-orange-600 peer-focus:text-sm">
                        Username
                    </label>
                    
                    <i class="bi bi-person absolute left-0 top-3 text-lg text-slate-400 peer-focus:text-orange-500 transition-colors"></i>
                </div>

                <div class="relative group">
                    <input type="password" name="password" id="password" required 
                        class="peer w-full h-12 bg-transparent border-b-2 border-slate-200 text-slate-900 placeholder-transparent focus:outline-none focus:border-orange-500 transition-colors duration-300 pl-8 pr-10"
                        placeholder="Password" />
                    
                    <label for="password" 
                        class="absolute left-8 -top-3.5 text-slate-500 text-sm transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-slate-400 peer-placeholder-shown:top-3 peer-focus:-top-3.5 peer-focus:text-orange-600 peer-focus:text-sm">
                        Password
                    </label>

                    <i class="bi bi-lock absolute left-0 top-3 text-lg text-slate-400 peer-focus:text-orange-500 transition-colors"></i>
                    
                    <button type="button" id="togglePassword" class="absolute right-0 top-3 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none">
                        <i class="bi bi-eye-slash" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="remember-me" class="peer h-4 w-4 cursor-pointer appearance-none rounded border border-slate-300 checked:bg-orange-500 checked:border-orange-500 transition-all">
                            <i class="bi bi-check text-white absolute left-0.5 top-0 opacity-0 peer-checked:opacity-100 text-xs pointer-events-none"></i>
                        </div>
                        <span class="text-slate-600 group-hover:text-slate-800 transition-colors">Remember me</span>
                    </label>
                    <a href="javascript:void(0);" class="text-orange-600 hover:text-orange-700 font-medium hover:underline transition-all">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" id="submitBtn" class="group relative w-full h-12 bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-300 transform active:scale-[0.98] overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <span id="btnText">Sign In</span>
                        <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1" id="btnIcon"></i>
                    </span>
                    <div id="btnLoader" class="hidden absolute inset-0 items-center justify-center bg-orange-600">
                        <div class="animate-spin rounded-full h-5 w-5 border-2 border-white border-t-transparent"></div>
                    </div>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-slate-500 text-sm">Don't have an account? <a href="#" class="text-orange-600 font-bold hover:underline">Register</a></p>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-slate-900 overflow-hidden group">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[2s] ease-out group-hover:scale-105" 
                 style="background-image: url('{{ asset('images/login_image.jpeg') }}');">
            </div>
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

            <div class="absolute bottom-0 left-0 p-12 text-white">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl shadow-xl">
                    <h3 class="text-3xl font-bold mb-3">Freshly Baked, Daily.</h3>
                    <p class="text-orange-100 leading-relaxed opacity-90">Manage your production, inventory, and sales all in one place. The sweetest way to handle business.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            
            // 1. Password Visibility Toggle
            $('#togglePassword').on('click', function() {
                const passwordInput = $('#password');
                const icon = $('#eyeIcon');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });

            // 2. Enhanced AJAX Login
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const btn = $('#submitBtn');
                const btnText = $('#btnText');
                const btnIcon = $('#btnIcon');
                const btnLoader = $('#btnLoader');
                const errorDiv = $('#errorMessage');
                const errorMessageText = errorDiv.find('.message-text');

                // UI Loading State
                btn.prop('disabled', true);
                btnLoader.removeClass('hidden').addClass('flex');
                btnText.addClass('opacity-0'); // Hide text but keep width
                btnIcon.addClass('opacity-0');
                errorDiv.addClass('hidden');
                form.removeClass('animate-shake'); // Reset shake

                $.ajax({
                    type: "POST",
                    url: "{{ route('login.submit') }}",
                    data: form.serialize(),
                    success: function(data) {
                        if (data.success) {
                            // Success Feedback
                            btnLoader.html('<i class="bi bi-check-lg text-xl"></i>');
                            setTimeout(function() {
                                window.location.href = data.redirect;
                            }, 500);
                        } else {
                            handleError(data.message || 'Login failed. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        let msg = 'An error occurred. Please try again.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        handleError(msg);
                    }
                });

                function handleError(msg) {
                    // Reset Button
                    btn.prop('disabled', false);
                    btnLoader.addClass('hidden').removeClass('flex');
                    btnText.removeClass('opacity-0');
                    btnIcon.removeClass('opacity-0');

                    // Show Error
                    errorMessageText.text(msg);
                    errorDiv.removeClass('hidden');
                    
                    // Trigger Shake Animation
                    form.addClass('animate-shake');
                    
                    // Optional: Clear password field on error
                    $('#password').val('');
                }
            });
        });
    </script>
</body>
</html>