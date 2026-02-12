<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BakeryMate ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/bakery.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/sweetalert2.all.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2.min.css') }}">
    <style>
        .modal {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            pointer-events: none;
        }

        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-900">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="flex flex-col w-64 transition-all duration-300 bg-slate-900 text-slate-300 flex-shrink-0 relative z-20 hidden md:flex">
            <!-- Brand -->
            <div class="flex items-center justify-center h-20 border-b border-slate-800 bg-slate-950 transition-all duration-300"
                id="sidebar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto transition-all duration-300"
                    id="sidebar-logo">
            </div>

            <!-- Navigation -->
            <div class="flex-1 overflow-y-auto py-4">
                <nav class="space-y-1 px-2">
                    <a href="{{ url('/adminDashboard') }}"
                        class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ Request::is('adminDashboard') ? 'bg-amber-600 text-white' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i
                            class="bi bi-grid-fill w-5 h-5 text-[1rem] min-w-[1.25rem] text-center {{ Request::is('adminDashboard') ? 'text-amber-200' : 'text-slate-400 group-hover:text-slate-300' }}"></i>
                        <span class="sidebar-text ml-3 truncate">Dashboard</span>
                    </a>

                    @if(isset($sidebarTopics))
                        @foreach($sidebarTopics as $topic)
                            @php
                                $interfaces = $topic->interfaces;
                                $interfaceCount = $interfaces->count();
                                $isActiveTopic = false;
                                foreach ($interfaces as $interface) {
                                    if (Request::is(ltrim($interface->path, '/') . '*')) {
                                        $isActiveTopic = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($interfaceCount === 1)
                                @php
                                    $interface = $interfaces->first();
                                @endphp
                                <a href="{{ url($interface->path) }}"
                                    class="group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors {{ $isActiveTopic ? 'bg-amber-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                                    <i
                                        class="{{ $topic->menu_icon ?? 'bi bi-collection' }} w-5 h-5 text-[1rem] min-w-[1.25rem] text-center {{ $isActiveTopic ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}"></i>
                                    <span class="sidebar-text ml-3 truncate">{{ $topic->topic_name }}</span>
                                </a>
                            @elseif($interfaceCount > 1)
                                <div class="relative">
                                    <button type="button"
                                        class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md transition-colors {{ $isActiveTopic ? 'bg-amber-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                                        onclick="toggleMenu('topic-{{ $topic->id }}')">
                                        <div class="flex items-center min-w-0">
                                            <i
                                                class="{{ $topic->menu_icon ?? 'bi bi-collection' }} w-5 h-5 text-[1rem] min-w-[1.25rem] text-center {{ $isActiveTopic ? 'text-white' : 'text-slate-400 group-hover:text-slate-300' }}"></i>
                                            <span class="sidebar-text ml-3 truncate">{{ $topic->topic_name }}</span>
                                        </div>
                                        <i class="bi {{ $isActiveTopic ? 'bi-chevron-up' : 'bi-chevron-down' }} w-4 h-4 transition-transform duration-200 sidebar-text"
                                            id="topic-{{ $topic->id }}-icon"></i>
                                    </button>
                                    <div class="{{ $isActiveTopic ? '' : 'hidden' }} space-y-1 ml-[22px] border-l-2 border-amber-500/75 pl-4 mt-1"
                                        id="topic-{{ $topic->id }}">
                                        @foreach($interfaces as $interface)
                                            @php
                                                $path = ltrim($interface->path, '/');
                                                $isInterfaceActive = Request::is($path) || Request::is($path . '/*');
                                            @endphp
                                            <a href="{{ url($interface->path) }}"
                                                class="block px-3 py-2 text-sm rounded-md transition-colors {{ $isInterfaceActive ? 'bg-slate-800 text-amber-500 font-medium' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' }}">
                                                {{ $interface->interface_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif


                </nav>
            </div>



            <!-- User Info (Optional Footer for Sidebar) -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="flex items-center text-sm text-slate-400">
                    <span class="truncate">v2.0.1</span>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Top Navbar -->
            <header
                class="h-16 bg-white border-b border-gray-200 flex items-center justify-between pr-6 pl-1 shadow-sm z-10">
                <!-- Mobile Menu Button -->
                <button type="button"
                    class="md:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none"
                    onclick="document.getElementById('sidebar').classList.toggle('hidden')">
                    <i class="bi bi-list"></i>
                </button>

                <!-- Desktop Menu Button -->
                <button type="button" id="desktop-menu-btn"
                    class="hidden md:inline-flex py-2 px-3 mr-2 rounded-md bg-slate-800 text-amber-600 hover:text-amber-600 hover:bg-slate-950 focus:outline-none"
                    onclick="toggleSidebar()">
                    <i class="bi bi-list text-xl"></i>
                </button>

                <!-- Page Title -->
                <div class="flex-1 flex items-center ml-4 md:ml-0">
                    <h1 class="text-xl font-semibold text-gray-800 tracking-tight">{{ $pageTitle ?? 'Admin Dashboard' }}
                    </h1>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Branch -->
                    <div class="hidden md:flex items-center">
                        <div
                            class="flex items-center gap-2 px-4 py-1.5 bg-slate-100 border border-slate-200 rounded-full">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Branch</span>
                            <span class="text-sm font-bold text-indigo-700">
                                {{ Auth::user()->currentBranch?->name ?? 'Not Selected' }}
                            </span>
                            <span class="relative flex h-2 w-2">
                                <span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                            </span>
                        </div>
                    </div>

                    <!-- POS View Button -->
                    <a href="{{ route('pos.index') }}"
                        class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="bi bi-shop mr-2"></i> POS View
                    </a>

                    <!-- Search (Hidden on Mobile) -->
                    <div class="hidden md:flex relative text-gray-400 focus-within:text-gray-600">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <input name="search" type="text"
                            class="block w-full h-10 rounded-full border-gray-200 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 hover:bg-white transition-colors"
                            placeholder="Search...">
                    </div>

                    <!-- Notifications -->
                    <button
                        class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <span class="absolute top-2 right-2 flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative ml-2">
                        <button type="button"
                            class="flex items-center gap-2 max-w-xs bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                            onclick="toggleProfileMenu()">
                            <span class="sr-only">Open user menu</span>
                            <div
                                class="h-9 w-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->first_name ?? 'U', 0, 1) . substr(Auth::user()->last_name ?? 'U', 0, 1)) }}
                            </div>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="user-menu-dropdown"
                            class="hidden absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm text-gray-900 font-medium truncate">
                                    {{ Auth::user()->first_name ?? 'User' }} {{ Auth::user()->last_name ?? '' }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->user_name ?? '' }}</p>
                            </div>

                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem"
                                tabindex="-1" id="user-menu-item-0">Your Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" role="menuitem"
                                tabindex="-1" id="user-menu-item-1">Settings</a>

                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"
                                    role="menuitem" tabindex="-1" id="user-menu-item-2">
                                    <i class="bi bi-box-arrow-right mr-2"></i>Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            @include('sweetalert::alert')

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6 md:p-8">
                <div class="max-w-8xl mx-auto">
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script>
        function toggleMenu(menuId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(menuId + '-icon');

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            }
        }

        function toggleProfileMenu() {
            const menu = document.getElementById('user-menu-dropdown');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (event) {
            const profileButton = document.getElementById('user-menu-button');
            const profileMenu = document.getElementById('user-menu-dropdown');

            if (profileButton && profileMenu && !profileButton.contains(event.target) && !profileMenu.contains(event.target)) {
                profileMenu.classList.add('hidden');
            }
        });
    </script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const logo = document.getElementById('sidebar-logo');
            const toggleBtn = document.getElementById('desktop-menu-btn');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const submenus = document.querySelectorAll('[id^="topic-"]'); // Select all submenus

            // Toggle Width
            if (sidebar.classList.contains('w-64')) {
                // Collapse
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');

                // Adjust Logo
                logo.classList.remove('h-16');
                logo.classList.add('h-8');

                // Hide Text
                sidebarTexts.forEach(el => el.classList.add('hidden'));

                // Hide all open submenus when collapsing
                submenus.forEach(menu => {
                    if (!menu.id.includes('icon')) { // Avoid selecting icons if IDs clash, though selector is safe enough usually
                        menu.classList.add('hidden');
                    }
                });

                // Save State
                localStorage.setItem('sidebarState', 'collapsed');
            } else {
                // Expand
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');

                // Adjust Logo
                logo.classList.remove('h-8');
                logo.classList.add('h-16');

                // Show Text
                sidebarTexts.forEach(el => el.classList.remove('hidden'));

                // Save State
                localStorage.setItem('sidebarState', 'expanded');
            }
        }

        // Initialize Sidebar State
        document.addEventListener('DOMContentLoaded', () => {
            const state = localStorage.getItem('sidebarState');
            if (state === 'collapsed') {
                toggleSidebar(); // Apply collapsed state
            }
        });

        // Enhance toggleMenu to auto-expand sidebar if collapsed
        const originalToggleMenu = window.toggleMenu;
        window.toggleMenu = function (menuId) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('w-20')) {
                toggleSidebar(); // Expand first
                setTimeout(() => originalToggleMenu(menuId), 300); // Wait for transition then toggle
            } else {
                originalToggleMenu(menuId);
            }
        };
    </script>
</body>

</html>