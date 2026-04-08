<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password - BakeryMate ERP</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        
        :root {
            --primary-color: {{ $settings->colors->primary ?? '#f97316' }};
            --primary-hover: {{ $settings->colors->accent ?? '#ea580c' }};
        }

        .bg-primary { background-color: var(--primary-color) !important; }
        .hover-bg-primary:hover { background-color: var(--primary-hover) !important; }
        .text-primary { color: var(--primary-color) !important; }
        .border-primary { border-color: var(--primary-color) !important; }
        .focus-border-primary:focus { border-color: var(--primary-color) !important; }

        /* Smooth Fade In */
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Strength Meter Styles */
        .strength-meter {
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
            position: relative;
        }
        .strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-orange-50 via-white to-orange-100 min-h-screen flex items-center justify-center p-4">

    <div class="fade-in-up bg-white w-full max-w-5xl h-auto md:min-h-[600px] rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] overflow-hidden flex flex-col md:flex-row">
        
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center relative">
            
            <div class="mb-8 text-center md:text-left">
                <img src="{{ asset($settings->logos->login ?? 'images/logo.png') }}" alt="BakeryMate Logo" class="h-16 w-auto inline-block mb-4">
                <h2 class="text-3xl font-bold text-slate-800">Security Update</h2>
                <p class="text-slate-500 text-sm mt-1">To keep your account secure, please set a new password before continuing.</p>
            </div>

            <form method="POST" action="{{ route('password.force_change.submit') }}" class="space-y-6">
                @csrf
                
                <!-- Password Field -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-bold text-slate-700">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required 
                            class="w-full h-12 px-4 bg-white border border-slate-300 rounded-lg text-slate-900 focus:border-orange-500 transition-all pr-12"
                            placeholder="••••••••" />
                        
                        <button type="button" class="password-toggle absolute right-4 top-3 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" data-target="password">
                            <i class="bi bi-eye-slash text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Strength Meter -->
                    <div class="strength-meter">
                        <div id="strengthBar" class="strength-bar"></div>
                    </div>
                    <p class="text-sm text-slate-500">
                        Password strength: <span id="strengthText" class="font-bold">None</span>
                    </p>
                    
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700">
                        Confirm password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                            class="w-full h-12 px-4 bg-white border border-slate-300 rounded-lg text-slate-900 focus:border-slate-400 transition-all pr-12"
                            placeholder="••••••••" />
                        
                        <button type="button" class="password-toggle absolute right-4 top-3 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none" data-target="password_confirmation">
                            <i class="bi bi-eye-slash text-xl"></i>
                        </button>
                    </div>
                    
                    <p class="text-sm text-slate-500">
                        Passwords match: <span id="matchStatusText" class="font-bold">no</span>
                    </p>
                </div>

                <button type="submit" class="group relative w-full h-14 bg-primary hover-bg-primary text-white font-bold rounded-2xl shadow-[0_10px_30px_rgba(249,115,22,0.3)] transition-all duration-300 transform active:scale-[0.98] overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-3">
                        <span>Update & Continue</span>
                        <i class="bi bi-shield-check transition-all"></i>
                    </span>
                    <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
            </form>

            <div class="mt-8 text-center">
                <p class="text-slate-400 text-xs">Logged in as: <span class="text-slate-600 font-semibold">{{ Auth::user()->user_name }}</span></p>
                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-red-500 text-sm font-medium transition-colors inline-flex items-center gap-2">
                        <i class="bi bi-box-arrow-left"></i>
                        Sign out
                    </button>
                </form>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-slate-900 overflow-hidden group">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-[3s] ease-out group-hover:scale-110" 
                 style="background-image: url('{{ asset('images/login_image.jpeg') }}');">
            </div>
            
            <div class="absolute inset-0 bg-gradient-to-tr from-slate-900/90 via-slate-900/40 to-transparent"></div>

            <div class="absolute inset-0 flex items-center justify-center p-12">
                <div class="glass-card p-8 rounded-3xl shadow-2xl max-w-sm">
                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-orange-500/30">
                        <i class="bi bi-shield-lock-fill text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-4">Account Security</h3>
                    <p class="text-slate-600 leading-relaxed mb-6">
                        We periodically require password updates to keep your business data and bakery operations secure.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <i class="bi bi-check-circle-fill text-green-500"></i>
                            Protects sensitive data
                        </div>
                        <div class="flex items-center gap-3 text-sm text-slate-700 font-medium">
                            <i class="bi bi-check-circle-fill text-green-500"></i>
                            Ensures only you have access
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Flash Messages Handler
            @if(session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif

            // Password Strength Logic
            function updateStrengthMeter() {
                const password = $('#password').val();
                const bar = $('#strengthBar');
                const text = $('#strengthText');
                
                let strength = 0;
                
                if (password.length > 0) {
                    // Base points for length
                    strength += Math.min(40, password.length * 4);
                    
                    // Variety points
                    if (/[A-Z]/.test(password)) strength += 15;
                    if (/[a-z]/.test(password)) strength += 15;
                    if (/[0-9]/.test(password)) strength += 15;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 15;
                }

                strength = Math.min(100, strength);
                bar.css('width', strength + '%');
                
                if (strength === 0) {
                    text.text('None').css('color', '#94a3b8');
                    bar.css('background-color', '#e2e8f0');
                } else if (strength <= 40) {
                    text.text('Weak').css('color', '#ef4444');
                    bar.css('background-color', '#ef4444');
                } else if (strength <= 65) {
                    text.text('Fair').css('color', '#f59e0b');
                    bar.css('background-color', '#f59e0b');
                } else if (strength <= 85) {
                    text.text('Good').css('color', '#10b981');
                    bar.css('background-color', '#10b981');
                } else {
                    text.text('Strong').css('color', '#059669');
                    bar.css('background-color', '#059669');
                }
            }

            // Real-time password matching
            function checkPasswordMatch() {
                const password = $('#password').val();
                const confirmation = $('#password_confirmation').val();
                const statusText = $('#matchStatusText');
                const confirmInput = $('#password_confirmation');

                if (password && confirmation) {
                    if (password === confirmation) {
                        statusText.text('yes').css('color', '#059669');
                        confirmInput.css('border-color', '#10b981').css('border-width', '2px');
                    } else {
                        statusText.text('no').css('color', '#ef4444');
                        confirmInput.css('border-color', '#ef4444').css('border-width', '2px');
                    }
                } else {
                    statusText.text('no').css('color', '#94a3b8');
                    confirmInput.css('border-color', '#cbd5e1').css('border-width', '1px');
                }
            }

            $('#password').on('input', function() {
                updateStrengthMeter();
                checkPasswordMatch();
            });

            $('#password_confirmation').on('input', checkPasswordMatch);

            // Password Visibility Toggle
            $('.password-toggle').on('click', function() {
                const targetId = $(this).data('target');
                const passwordInput = $('#' + targetId);
                const icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('bi-eye-slash').addClass('bi-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('bi-eye').addClass('bi-eye-slash');
                }
            });
        });
    </script>
</body>
</html>
