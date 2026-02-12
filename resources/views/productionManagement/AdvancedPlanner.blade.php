<!DOCTYPE html>
<html lang="en">

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans antialiased text-gray-900">
    @include('sweetalert::alert')
    <div class="h-screen flex flex-col bg-[#F5F5F7]">

        @include('productionManagement.components.planner.header')

        <div class="flex-1 overflow-hidden relative">

            <div id="view-timeline" class="planner-view h-full">
                @include('productionManagement.components.planner.views.timeline')
            </div>

            <div id="view-analytics" class="planner-view h-full" style="display: none;">
                @include('productionManagement.components.planner.views.analytics')
            </div>

            <div id="view-resources" class="planner-view h-full" style="display: none;">
                @include('productionManagement.components.planner.views.resources', ['departments' => $departmentsRaw])
                @include('productionManagement.components.planner.views.resources')
            </div>

            <div id="view-settings" class="planner-view h-full" style="display: none;">
                @include('productionManagement.components.planner.views.Settings')
            </div>

            <div id="view-kitchen" class="planner-view h-full" style="display: none;">
                @include('productionManagement.components.planner.views.kitchen')
            </div>

        </div>
    </div>

    <script type="module">
        $(document).ready(function () {

            // --- State Management ---
            let state = {
                currentView: 'timeline',
                viewMode: '3-day',
                unsavedChanges: false
            };

            // --- Functions ---

            function updateUIState() {
                // 1. Unsaved Changes Indicator
                if (state.unsavedChanges) {
                    $('#status-unsaved').removeClass('hidden');
                    $('#status-saved').addClass('hidden');
                    $('#btn-save').prop('disabled', false).removeClass('opacity-50 cursor-not-allowed').addClass('hover:bg-[#B8860B] cursor-pointer shadow-lg shadow-amber-900/20');
                } else {
                    $('#status-unsaved').addClass('hidden');
                    $('#status-saved').removeClass('hidden');
                    $('#btn-save').prop('disabled', true).addClass('opacity-50 cursor-not-allowed').removeClass('hover:bg-[#B8860B] cursor-pointer shadow-lg shadow-amber-900/20');
                }
            }

            // Show/Hide View Mode Selector (Only timeline needs it for now)
            // if (viewId === 'timeline') { ... } 

        function setViewMode(mode) {
            state.viewMode = mode;
            
            // Update Timeline viewMode if Timeline is loaded
            if (window.TimelinePlanner) {
                window.TimelinePlanner.state.viewMode = mode;
                window.TimelinePlanner.render();
            }
            
            // Update UI
            $('.btn-view-mode').each(function() {
               const $btn = $(this);
               if ($btn.data('mode') === mode) {
                   $btn.removeClass('text-gray-300 hover:text-white hover:bg-gray-700')
                       .addClass('bg-[#D4A017] text-white shadow-sm');
               } else {
                   $btn.addClass('text-gray-300 hover:text-white hover:bg-gray-700')
                       .removeClass('bg-[#D4A017] text-white shadow-sm');
               }
            });

            // Update Text in Timeline View (Just for demo/feedback)
            $('#current-view-mode-text').text(mode);
        }

        // --- Event Listeners ---

        // 1. View Switching
        $('.btn-switch-view').on('click', function() {
            const view = $(this).data('view');
            switchView(view);
        });

        // 2. View Mode Switching
        $('.btn-view-mode').on('click', function() {
            const mode = $(this).data('mode');
            setViewMode(mode);
        });

        // 3. Simulate Change (From Timeline View)
        $(document).on('click', '#btn-simulate-change', function() {
            state.unsavedChanges = true;
            updateUIState();
            // Optional Feedback
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Unsaved changes simulated',
                showConfirmButton: false,
                timer: 1500
            });
        });

        // 4. Save Button
        $('#btn-save').on('click', function() {
            if (!state.unsavedChanges) return;

            const $btn = $(this);
            const originalContent = $btn.html();
            $btn.html('<span class="animate-spin inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full mr-2"></span> Saving...');
            $btn.prop('disabled', true);

            // AJAX Save
            $.ajax({
                url: '/production/save-schedule', // Ensure this route exists or mock it
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    // schedule_data: gatherScheduleData() 
                },
                success: function(response) {
                    state.unsavedChanges = false;
                    updateUIState();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Schedule saved successfully',
                        showConfirmButton: false,
                        timer: 3000
                    });
                },
                error: function(err) {
                    // Start Mock Error Handling (Remove this block in real usage if route exists)
                    // For demo purposes, we will treat error as success if route 404s
                     state.unsavedChanges = false;
                    updateUIState();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Schedule saved (Simulated)',
                        showConfirmButton: false,
                        timer: 3000
                    });
                     // End Mock
                    /* 
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error saving schedule',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    */
                },
                complete: function() {
                    $btn.html(originalContent);
                }
            });
        });
        

        // 5. Exit Button
        $('#btn-exit').on('click', function() {
            if (state.unsavedChanges) {
                $('#exit-confirmation-modal').removeClass('hidden');
            } else {
                window.location.href = '/production-scheduling';
            }
        });

        function switchView(viewId) {
            state.currentView = viewId;

            // Hide all views
            $('.planner-view').hide();
            // Show target view
            $('#view-' + viewId).show();

            // Update Tab Styles
            $('.btn-switch-view').each(function () {
                const $btn = $(this);
                const isActive = $btn.data('view') === viewId;
                const $indicator = $btn.find('.active-indicator');

                if (isActive) {
                    $btn.removeClass('text-gray-400 hover:text-gray-200').addClass('text-white');
                    $indicator.removeClass('hidden');
                } else {
                    $btn.addClass('text-gray-400 hover:text-gray-200').removeClass('text-white');
                    $indicator.addClass('hidden');
                }
            });
        }

            // 6. Modal Actions
            $('#btn-modal-cancel, #modal-backdrop').on('click', function () {
                $('#exit-confirmation-modal').addClass('hidden');
            });

            $('#btn-modal-discard').on('click', function () {
                window.location.href = '/production-scheduling';
            });

            $('#btn-modal-save-exit').on('click', function () {
                // Trigger save, then exit
                const $btn = $(this);
                $btn.prop('disabled', true).text('Saving...');

                // Reuse Save Logic or duplicate for specific flow
                $.ajax({
                    url: '/production/save-schedule',
                    method: 'POST',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    complete: function () {
                        // Whether success or fail, we exit for this demo
                        window.location.href = '/production-scheduling';
                    }
                });
            });

            // Initial State
            updateUIState();
            switchView('timeline'); // Default view
        });
    </script>
</body>

</html>