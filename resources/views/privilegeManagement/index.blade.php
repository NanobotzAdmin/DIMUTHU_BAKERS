@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 pb-24"> 
    <div class="bg-white border-b border-gray-200 shadow-sm ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div>
                    <h1 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-indigo-600"></i>
                        Privilege Management
                    </h1>
                    <p class="text-xs text-gray-500 mt-0.5">Configure access control levels</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="expandAll()" class="text-xs font-medium text-gray-600 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 px-3 py-1.5 rounded transition">
                        <i class="fas fa-expand-arrows-alt mr-1"></i> Expand All
                    </button>
                    <button onclick="collapseAll()" class="text-xs font-medium text-gray-600 hover:text-indigo-600 bg-gray-100 hover:bg-indigo-50 px-3 py-1.5 rounded transition">
                        <i class="fas fa-compress-arrows-alt mr-1"></i> Collapse All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    
                    <div class="md:col-span-4">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                            Manage Context
                        </label>
                        <div class="flex bg-gray-100 p-1 rounded-lg">
                            <label class="flex-1 text-center cursor-pointer">
                                <input type="radio" name="manage_type" value="user" checked onchange="toggleType()" class="peer sr-only">
                                <div class="px-4 py-2 rounded-md text-sm font-medium text-gray-500 peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all duration-200">
                                    <i class="fas fa-user mr-2"></i>By User
                                </div>
                            </label>
                            <label class="flex-1 text-center cursor-pointer">
                                <input type="radio" name="manage_type" value="role" onchange="toggleType()" class="peer sr-only">
                                <div class="px-4 py-2 rounded-md text-sm font-medium text-gray-500 peer-checked:bg-white peer-checked:text-indigo-600 peer-checked:shadow-sm transition-all duration-200">
                                    <i class="fas fa-user-tag mr-2"></i>By Role
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="md:col-span-5 relative">
                        <div id="user-select-container" class="transition-opacity duration-300">
                            <label for="user-select" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Target User</label>
                            <div class="relative">
                                <select id="user-select" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg border bg-gray-50 hover:bg-white transition-colors" onchange="loadPrivileges()">
                                    <option value="">-- Choose an employee --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }} ({{ $user->contact_no }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="role-select-container" class="hidden transition-opacity duration-300">
                             <label for="role-select" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Select Target Role</label>
                             <select id="role-select" class="block w-full pl-3 pr-10 py-2.5 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg border bg-gray-50 hover:bg-white transition-colors" onchange="loadPrivileges()">
                                  <option value="">-- Choose a role --</option>
                                  @foreach($roles as $role)
                                      <option value="{{ $role->id }}">{{ $role->user_role_name }}</option>
                                  @endforeach
                             </select>
                        </div>
                    </div>

                    <div class="md:col-span-3 text-right">
                         <div id="status-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <span class="w-2 h-2 mr-2 bg-gray-400 rounded-full" id="status-dot"></span>
                            <span id="status-msg">Waiting for selection...</span>
                         </div>
                    </div>
                </div>
            </div>
            <div id="loading-bar" class="h-1 w-full bg-indigo-100 hidden">
                <div class="h-full bg-indigo-600 animate-pulse w-full"></div>
            </div>
        </div>

        <div id="privilege-tree" class="space-y-6">
            @foreach($topics as $topic)
            <div class="topic-group bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all hover:shadow-md">
                
                <div class="bg-gray-50/50 px-6 py-4 flex items-center justify-between cursor-pointer group select-none" onclick="toggleSection(this)">
                    <div class="flex items-center gap-4">
                        <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-200">
                            <i class="fas fa-layer-group text-lg"></i>
                        </span>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 group-hover:text-indigo-700 transition-colors">{{ $topic->topic_name }}</h3>
                            <p class="text-xs text-gray-400">{{ $topic->interfaces->count() }} Interfaces available</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right transform transition-transform duration-300 text-gray-400 group-hover:text-indigo-500"></i>
                </div>
                
                <div class="hidden border-t border-gray-100 animate-fade-in-down">
                    <div class="p-6 space-y-8">
                        @foreach($topic->interfaces as $interface)
                        <div class="interface-group relative pl-4 md:pl-0">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-gray-100 rounded md:hidden"></div>

                            <div class="flex items-center gap-2 mb-4">
                                <i class="fas fa-cube text-gray-400 text-sm"></i>
                                <h4 class="font-semibold text-gray-700 uppercase tracking-wide text-xs">{{ $interface->interface_name }}</h4>
                                <div class="flex-grow border-b border-gray-100 ml-2"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($interface->components as $component)
                                <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:border-indigo-300 hover:bg-indigo-50/30 transition-all duration-200 group select-none bg-white">
                                    <input type="checkbox" 
                                           name="privileges[]" 
                                           value="{{ $component->id }}" 
                                           class="privilege-checkbox peer sr-only">
                                    
                                    <div class="w-5 h-5 border-2 border-gray-300 rounded flex items-center justify-center mr-3 peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-colors">
                                        <i class="bi bi-check text-white text-lg"></i>
                                    </div>

                                    <div class="flex-1">
                                        <span class="text-sm text-gray-600 font-medium peer-checked:text-indigo-900 group-hover:text-gray-900">
                                            {{ $component->components_name }}
                                        </span>
                                    </div>
                                    
                                    <div class="absolute inset-0 rounded-lg ring-2 ring-indigo-500 ring-opacity-0 peer-checked:ring-opacity-100 pointer-events-none transition-all duration-200"></div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    <div class="fixed bottom-0 w-full bg-white border-t border-gray-200 shadow-sm sticky top-0 z-20">
        <div class="max-w-7xl mx-auto p-4 flex justify-between items-center">
            <div class="text-sm text-gray-500 hidden sm:block">
                <i class="fas fa-info-circle mr-1"></i> Changes are applied immediately upon saving.
            </div>
            <button onclick="savePrivileges()" 
                    id="save-btn" 
                    disabled
                    class="ml-auto sm:ml-0 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-8 rounded-lg shadow-lg shadow-indigo-500/30 transition-all transform active:scale-95 flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

</div>

<style>
    /* Simple animation for the dropdown reveal */
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
    @keyframes fadeInDown {
        0% { opacity: 0; transform: translateY(-10px); }
        100% { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    // --- Configuration ---
    toastr.options = { "closeButton": true, "progressBar": true, "positionClass": "toast-top-right", "timeOut": "3000" };

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // --- UI Logic ---

    function toggleSection(header) {
        const content = $(header).next();
        const icon = $(header).find('.fa-chevron-right');
        
        if (content.hasClass('hidden')) {
            content.removeClass('hidden');
            icon.addClass('rotate-90');
        } else {
            content.addClass('hidden');
            icon.removeClass('rotate-90');
        }
    }

    function expandAll() {
        $('#privilege-tree .hidden').removeClass('hidden');
        $('#privilege-tree .fa-chevron-right').addClass('rotate-90');
    }

    function collapseAll() {
        // More specific selector to ensure we only target the content divs
        $('#privilege-tree .topic-group > div:nth-child(2)').addClass('hidden'); 
        $('#privilege-tree .fa-chevron-right').removeClass('rotate-90');
    }

    function updateStatus(msg, type = 'neutral') {
        const $badge = $('#status-badge');
        const $dot = $('#status-dot');
        const $msg = $('#status-msg');

        $msg.text(msg);

        // Reset classes
        $dot.removeClass('bg-gray-400 bg-yellow-400 bg-green-500 bg-red-500');
        $badge.removeClass('bg-gray-100 bg-yellow-50 bg-green-50 bg-red-50 text-gray-800 text-yellow-800 text-green-800 text-red-800');

        if(type === 'loading') {
            $dot.addClass('bg-yellow-400 animate-pulse');
            $badge.addClass('bg-yellow-50 text-yellow-800');
            $('#loading-bar').removeClass('hidden');
        } else if (type === 'success') {
            $dot.addClass('bg-green-500');
            $badge.addClass('bg-green-50 text-green-800');
            $('#loading-bar').addClass('hidden');
        } else if (type === 'error') {
            $dot.addClass('bg-red-500');
            $badge.addClass('bg-red-50 text-red-800');
            $('#loading-bar').addClass('hidden');
        } else {
            $dot.addClass('bg-gray-400');
            $badge.addClass('bg-gray-100 text-gray-800');
            $('#loading-bar').addClass('hidden');
        }
    }

    function toggleType() {
        const type = $('input[name="manage_type"]:checked').val();
        
        // Reset Logic
        $('#user-select').val('');
        $('#role-select').val('');
        $('.privilege-checkbox').prop('checked', false);
        $('#save-btn').prop('disabled', true);

        // UI Logic
        if (type === 'user') {
            $('#user-select-container').removeClass('hidden');
            $('#role-select-container').addClass('hidden');
            updateStatus('Select a user to begin.');
        } else {
            $('#user-select-container').addClass('hidden');
            $('#role-select-container').removeClass('hidden');
            updateStatus('Select a role to begin.');
        }
    }

    function loadPrivileges() {
        const type = $('input[name="manage_type"]:checked').val();
        const id = (type === 'user') ? $('#user-select').val() : $('#role-select').val();
        
        $('.privilege-checkbox').prop('checked', false);
        
        if (!id) {
            $('#save-btn').prop('disabled', true);
            updateStatus('Waiting for selection...');
            return;
        }

        updateStatus(`Fetching ${type} data...`, 'loading');
        $('#save-btn').prop('disabled', true);

        $.post("{{ route('privilegeManagement.getPrivileges') }}", { id: id, type: type })
         .done(function(resp) {
             if (resp.success) {
                 resp.privileges.forEach(id => {
                     $(`.privilege-checkbox[value="${id}"]`).prop('checked', true);
                 });
                 
                 updateStatus('Privileges loaded.', 'success');
                 $('#save-btn').prop('disabled', false);
                 
                 // Smart expand only relevant sections
                 let hasSelection = false;
                 $('.privilege-checkbox:checked').each(function() {
                     hasSelection = true;
                     const group = $(this).closest('.topic-group');
                     group.find('> div:nth-child(2)').removeClass('hidden');
                     group.find('.fa-chevron-right').addClass('rotate-90');
                 });

                 if(!hasSelection) {
                     toastr.info('No existing privileges found for this selection.');
                 }
                 
             } else {
                 updateStatus('Failed to load data.', 'error');
                 toastr.error('Failed to load privileges.');
             }
         })
         .fail(function() {
             updateStatus('Connection error.', 'error');
             toastr.error('Error connecting to server.');
         });
    }

    function savePrivileges() {
        const type = $('input[name="manage_type"]:checked').val();
        const id = (type === 'user') ? $('#user-select').val() : $('#role-select').val();

        if (!id) return;

        const selectedComponents = [];
        $('.privilege-checkbox:checked').each(function() {
            selectedComponents.push($(this).val());
        });

        // UI Feedback
        const originalBtnHtml = $('#save-btn').html();
        $('#save-btn').html('<i class="fas fa-circle-notch fa-spin"></i> Saving...').prop('disabled', true);

        $.post("{{ route('privilegeManagement.updatePrivilege') }}", { 
            id: id,
            type: type,
            component_ids: selectedComponents
        })
        .done(function(resp) {
            if (resp.success) {
                toastr.success('Privileges updated successfully!');
                updateStatus('Changes saved successfully.', 'success');
            } else {
                toastr.error('Failed to update privileges.');
                updateStatus('Save failed.', 'error');
            }
        })
        .fail(function() {
            toastr.error('Server error.');
            updateStatus('Server error during save.', 'error');
        })
        .always(function() {
            $('#save-btn').html(originalBtnHtml).prop('disabled', false);
        });
    }
</script>
@endsection