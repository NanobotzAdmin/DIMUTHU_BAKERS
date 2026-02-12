@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Interface Management</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-[calc(100vh-150px)]">
            <!-- Topics Column -->
            <div class="bg-white rounded-lg shadow-md flex flex-col h-full border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                    <h2 class="font-semibold text-gray-700">Topics</h2>
                    <div class="flex gap-2">
                        <button id="save-topic-order-btn" onclick="saveTopicOrder()"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded transition disabled:opacity-50 disabled:cursor-not-allowed hidden">
                            Save Order
                        </button>
                        <button onclick="openTopicModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded transition">
                            + Add
                        </button>
                    </div>
                </div>
                <div class="overflow-y-auto flex-1 p-2 space-y-2" id="topic-list">
                    @foreach($topics as $topic)
                        <div class="topic-item cursor-pointer p-3 rounded-md hover:bg-blue-50 border border-gray-200 hover:border-blue-100 group transition"
                            data-id="{{ $topic->id }}" onclick="selectTopic(this, '{{ $topic->id }}')">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-gray-800 flex items-center gap-2"><i
                                        class="{{ $topic->menu_icon }}"></i>{{ $topic->topic_name }}</span>
                                <div class="flex items-center">
                                    @if($topic->show_in_slidebar == 1)
                                        <span class="bg-indigo-100 text-indigo-800 text-[10px] px-2 py-0.5 rounded mr-2 font-semibold border border-indigo-200">Sidebar</span>
                                    @endif
                                    <div class="flex flex-col mr-2">
                                        <button onclick="moveTopic(event, this, 'up')" class="text-xs text-gray-400 hover:text-blue-600 px-1 leading-none mb-0.5"><i class="bi bi-caret-up-fill"></i></button>
                                        <button onclick="moveTopic(event, this, 'down')" class="text-xs text-gray-400 hover:text-blue-600 px-1 leading-none mt-0.5"><i class="bi bi-caret-down-fill"></i></button>
                                    </div>
                                    <button onclick="editTopic(event, '{{ $topic }}')"
                                        class="text-xs text-red-500 hover:text-blue-600 mr-2"><i
                                            class="bi bi-pencil"></i></button>
                                </div>
                            </div>
                            <div class="hidden json-data">{{ json_encode($topic->interfaces) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Interfaces Column -->
            <div class="bg-white rounded-lg shadow-md flex flex-col h-full border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                    <h2 class="font-semibold text-gray-700">Interfaces</h2>
                    <div class="flex gap-2">
                        <button id="save-order-btn" onclick="saveInterfaceOrder()"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1 rounded transition disabled:opacity-50 disabled:cursor-not-allowed hidden">
                            Save Order
                        </button>
                        <button id="add-interface-btn" onclick="openInterfaceModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            + Add
                        </button>
                    </div>
                </div>
                <div class="overflow-y-auto flex-1 p-2 space-y-2" id="interface-list">
                    <div class="text-gray-400 text-center mt-10 italic">Select a topic to view interfaces</div>
                </div>
            </div>

            <!-- Components Column -->
            <div class="bg-white rounded-lg shadow-md flex flex-col h-full border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                    <h2 class="font-semibold text-gray-700">Components</h2>
                    <button id="add-component-btn" onclick="openComponentModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                        + Add
                    </button>
                </div>
                <div class="overflow-y-auto flex-1 p-2 space-y-2" id="component-list">
                    <div class="text-gray-400 text-center mt-10 italic">Select an interface to view components</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Template (Reused for Topic, Interface, Component) -->
    <div id="genericModal"
        class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Title</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
            </div>
            <form id="genericForm">
                @csrf
                <input type="hidden" id="entry_id" name="id">
                <input type="hidden" id="parent_id" name="parent_id">

                <div id="modalFields">
                    <!-- Fields injected via JS -->
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancel</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTopicId = null;
        let currentInterfaceId = null;
        let interfacesData = {}; // Store interfaces by topic ID if needed, or parse from DOM

        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function selectTopic(element, id) {
            $('.topic-item').removeClass('bg-blue-100 border-blue-300 ring-1 ring-blue-300').addClass('border-gray-200');
            $(element).addClass('bg-blue-100 border-blue-300 ring-1 ring-blue-300').removeClass('border-gray-200');

            currentTopicId = id;
            currentInterfaceId = null;
            $('#add-interface-btn').prop('disabled', false);
            $('#add-component-btn').prop('disabled', true);

            const interfaces = JSON.parse($(element).find('.json-data').text());
            renderInterfaces(interfaces);
            $('#component-list').html('<div class="text-gray-400 text-center mt-10 italic">Select an interface to view components</div>');
        }

        function renderInterfaces(interfaces) {
            const container = $('#interface-list');
            container.empty();

            if (interfaces.length === 0) {
                container.html('<div class="text-gray-400 text-center mt-4">No interfaces found</div>');
                return;
            }

            interfaces.forEach((iface, index) => {
                // Store components in data attribute
                const components = iface.components || [];

                // Visual indicator for show_in_slidebar
                const sidebarBadge = iface.show_in_slidebar == 1
                    ? '<span class="bg-indigo-100 text-indigo-800 text-[10px] px-2 py-0.5 rounded mr-2 font-semibold border border-indigo-200">Sidebar</span>'
                    : '';

                const item = `
                            <div class="interface-item cursor-pointer p-3 rounded-md hover:bg-purple-50 border border-gray-200 hover:border-purple-100 group transition" 
                                 data-id="${iface.id}"
                                 onclick="selectInterface(this, ${iface.id}, '${escapeHtml(JSON.stringify(components))}')">
                                <div class="flex justify-between items-center">
                                    <div>
                                         <div class="font-medium text-gray-800">${iface.interface_name}</div>
                                         <div class="text-xs text-gray-500">${iface.path || ''}</div>
                                    </div>
                                    <div class="flex items-center">
                                        ${sidebarBadge}
                                        <div class="flex flex-col mr-2">
                                            <button onclick="moveInterface(event, this, 'up')" class="text-xs text-gray-400 hover:text-blue-600 px-1 leading-none mb-0.5"><i class="bi bi-caret-up-fill"></i></button>
                                            <button onclick="moveInterface(event, this, 'down')" class="text-xs text-gray-400 hover:text-blue-600 px-1 leading-none mt-0.5"><i class="bi bi-caret-down-fill"></i></button>
                                        </div>
                                        <button onclick="editInterface(event, ${escapeHtml(JSON.stringify(iface))})" class="text-xs text-red-500 hover:text-blue-600 mr-2"><i class="bi bi-pencil"></i></button>
                                    </div>
                                </div>
                            </div>
                         `;
                container.append(item);
            });
        }

        function selectInterface(element, id, componentsJson) {
            $('.interface-item').removeClass('bg-purple-100 border-purple-300 ring-1 ring-purple-300').addClass('border-gray-200');
            $(element).addClass('bg-purple-100 border-purple-300 ring-1 ring-purple-300').removeClass('border-gray-200');

            currentInterfaceId = id;
            $('#add-component-btn').prop('disabled', false);

            try {
                const components = JSON.parse(componentsJson);
                renderComponents(components);
            } catch (e) {
                console.error('Error parsing components', e);
                renderComponents([]);
            }
        }

        function renderComponents(components) {
            const container = $('#component-list');
            container.empty();

            if (components.length === 0) {
                container.html('<div class="text-gray-400 text-center mt-4">No components found</div>');
                return;
            }

            components.forEach(comp => {
                const item = `
                            <div class="component-item p-3 rounded-md hover:bg-green-50 border border-gray-200 hover:border-green-100 group transition">
                                <div class="flex justify-between items-center">
                                    <div>
                                         <div class="font-medium text-gray-800">${comp.components_name}</div>
                                         <div class="text-xs text-mono text-gray-500 bg-gray-100 px-1 rounded inline-block">#${comp.component_id || ''}</div>
                                    </div>
                                    <div class="">
                                        <button onclick="editComponent(event, ${escapeHtml(JSON.stringify(comp))})" class="text-xs text-red-500 hover:text-blue-600 mr-2"><i class="bi bi-pencil"></i></button>
                                    </div>
                                </div>
                            </div>
                        `;
                container.append(item);
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            // A simple escape for JSON string to be safe in HTML attribute
            return text.replace(/"/g, '&quot;');
        }

        // --- Modal Logic ---

        function openTopicModal() {
            $('#modalTitle').text('Add Topic');
            $('#entry_id').val('');
            $('#parent_id').val('');
            $('#genericForm').data('action', "{{ route('interfaceManagement.storeTopic') }}");

            $('#modalFields').html(`
                        <label class="block text-sm font-medium text-gray-700 mb-1">Topic Name</label>
                        <input type="text" name="topic_name" class="w-full border rounded px-3 py-2 mb-3" required>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Menu Icon</label>
                        <input type="text" name="menu_icon" class="w-full border rounded px-3 py-2 mb-3">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="show_in_slidebar" id="show_in_slidebar" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                            <label for="show_in_slidebar" class="ml-2 block text-sm text-gray-900">Show in Sidebar</label>
                        </div>
                    `);
            $('#genericModal').removeClass('hidden');
        }

        function editTopic(event, topic) {
            if (typeof topic === 'string') {
                topic = JSON.parse(topic);
            }
            event.stopPropagation();
            $('#modalTitle').text('Edit Topic');
            $('#entry_id').val(topic.id);
            $('#genericForm').data('action', "{{ route('interfaceManagement.updateTopic') }}");

            $('#modalFields').html(`
                        <label class="block text-sm font-medium text-gray-700 mb-1">Topic Name</label>
                        <input type="text" name="topic_name" value="${topic.topic_name}" class="w-full border rounded px-3 py-2 mb-3" required>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Menu Icon</label>
                        <input type="text" name="menu_icon" value="${topic.menu_icon || ''}" class="w-full border rounded px-3 py-2 mb-3">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="show_in_slidebar" id="edit_show_in_slidebar" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${topic.show_in_slidebar == 1 ? 'checked' : ''}>
                            <label for="edit_show_in_slidebar" class="ml-2 block text-sm text-gray-900">Show in Sidebar</label>
                        </div>
                    `);
            $('#genericModal').removeClass('hidden');
        }

        function openInterfaceModal() {
            $('#modalTitle').text('Add Interface');
            $('#entry_id').val('');
            // For storeInterface, we expect pm_interface_topic_id as 'parent_id' logic? 
            // No, controller expects explicit names. Let's add hidden field.
            $('#genericForm').data('action', "{{ route('interfaceManagement.storeInterface') }}");

            $('#modalFields').html(`
                        <input type="hidden" name="pm_interface_topic_id" value="${currentTopicId}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Interface Name</label>
                        <input type="text" name="interface_name" class="w-full border rounded px-3 py-2 mb-3" required>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Path (Route)</label>
                        <input type="text" name="path" class="w-full border rounded px-3 py-2 mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class</label>
                        <input type="text" name="icon_class" class="w-full border rounded px-3 py-2 mb-3">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="show_in_slidebar" id="iface_show_in_slidebar" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" checked>
                            <label for="iface_show_in_slidebar" class="ml-2 block text-sm text-gray-900">Show in Sidebar</label>
                        </div>
                    `);
            $('#genericModal').removeClass('hidden');
        }

        function editInterface(event, iface) {
            event.stopPropagation();
            $('#modalTitle').text('Edit Interface');
            $('#entry_id').val(iface.id);
            $('#genericForm').data('action', "{{ route('interfaceManagement.updateInterface') }}");

            $('#modalFields').html(`
                        <label class="block text-sm font-medium text-gray-700 mb-1">Interface Name</label>
                        <input type="text" name="interface_name" value="${iface.interface_name}" class="w-full border rounded px-3 py-2 mb-3" required>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Path (Route)</label>
                        <input type="text" name="path" value="${iface.path || ''}" class="w-full border rounded px-3 py-2 mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class</label>
                        <input type="text" name="icon_class" value="${iface.icon_class || ''}" class="w-full border rounded px-3 py-2 mb-3">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="show_in_slidebar" id="edit_iface_show_in_slidebar" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" ${iface.show_in_slidebar == 1 ? 'checked' : ''}>
                            <label for="edit_iface_show_in_slidebar" class="ml-2 block text-sm text-gray-900">Show in Sidebar</label>
                        </div>
                    `);
            $('#genericModal').removeClass('hidden');
        }

        function openComponentModal() {
            $('#modalTitle').text('Add Component');
            $('#entry_id').val('');
            $('#genericForm').data('action', "{{ route('interfaceManagement.storeComponent') }}");

            $('#modalFields').html(`
                        <input type="hidden" name="pm_interface_id" value="${currentInterfaceId}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Component Name</label>
                        <input type="text" name="components_name" class="w-full border rounded px-3 py-2 mb-3" required>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Component ID (HTML ID)</label>
                        <input type="text" name="component_id" class="w-full border rounded px-3 py-2 mb-3">
                    `);
            $('#genericModal').removeClass('hidden');
        }

        function editComponent(event, comp) {
            event.stopPropagation();
            $('#modalTitle').text('Edit Component');
            $('#entry_id').val(comp.id);
            $('#genericForm').data('action', "{{ route('interfaceManagement.updateComponent') }}");

            $('#modalFields').html(`
                        <label class="block text-sm font-medium text-gray-700 mb-1">Component Name</label>
                        <input type="text" name="components_name" value="${comp.components_name}" class="w-full border rounded px-3 py-2 mb-3" required>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Component ID (HTML ID)</label>
                        <input type="text" name="component_id" value="${comp.component_id || ''}" class="w-full border rounded px-3 py-2 mb-3">
                    `);
            $('#genericModal').removeClass('hidden');
        }

        function closeModal() {
            $('#genericModal').addClass('hidden');
        }

        $('#genericForm').submit(function (e) {
            e.preventDefault();
            const url = $(this).data('action');
            const formData = $(this).serialize();

            $.post(url, formData)
                .done(function (resp) {
                    if (resp.success) {
                        toastr.success(resp.message || 'Operation successful');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(resp.message || 'Error occurred');
                    }
                })
                .fail(function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = 'Validation Error: ';
                        for (let field in errors) {
                            errorMsg += errors[field][0] + ' ';
                        }
                        toastr.error(errorMsg);
                    } else {
                        toastr.error('Server error: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    }
                });
        });

        function moveInterface(event, btn, direction) {
            event.stopPropagation();
            const item = $(btn).closest('.interface-item');
            
            if (direction === 'up') {
                const prev = item.prev('.interface-item');
                if (prev.length) {
                    item.insertBefore(prev);
                    enableSaveOrder();
                }
            } else {
                const next = item.next('.interface-item');
                if (next.length) {
                    item.insertAfter(next);
                    enableSaveOrder();
                }
            }
        }

        function enableSaveOrder() {
            const btn = $('#save-order-btn');
            btn.removeClass('hidden');
        }

        function saveInterfaceOrder() {
            const orderedIds = [];
            $('.interface-item').each(function() {
                orderedIds.push($(this).data('id'));
            });

            $.post("{{ route('interfaceManagement.saveInterfaceOrder') }}", {
                ordered_ids: orderedIds,
                _token: '{{ csrf_token() }}'
            }).done(function(resp) {
                if(resp.success) {
                    toastr.success(resp.message);
                    $('#save-order-btn').addClass('hidden');
                    // Reload to update sidebar
                    setTimeout(() => location.reload(), 500);
                } else {
                    toastr.error(resp.message);
                }
            }).fail(function(xhr) {
                toastr.error('Failed to save order');
            });
        }

        // Topic Ordering Logic
        function moveTopic(event, btn, direction) {
            event.stopPropagation();
            const item = $(btn).closest('.topic-item');
            
            if (direction === 'up') {
                const prev = item.prev('.topic-item');
                if (prev.length) {
                    item.insertBefore(prev);
                    enableSaveTopicOrder();
                }
            } else {
                const next = item.next('.topic-item');
                if (next.length) {
                    item.insertAfter(next);
                    enableSaveTopicOrder();
                }
            }
        }

        function enableSaveTopicOrder() {
            const btn = $('#save-topic-order-btn');
            btn.removeClass('hidden');
        }

        function saveTopicOrder() {
            const orderedIds = [];
            $('.topic-item').each(function() {
                orderedIds.push($(this).data('id'));
            });

            $.post("{{ route('interfaceManagement.saveTopicOrder') }}", {
                ordered_ids: orderedIds,
                _token: '{{ csrf_token() }}'
            }).done(function(resp) {
                if(resp.success) {
                    toastr.success(resp.message);
                    $('#save-topic-order-btn').addClass('hidden');
                    // Reload to update sidebar
                    setTimeout(() => location.reload(), 500);
                } else {
                    toastr.error(resp.message);
                }
            }).fail(function(xhr) {
                toastr.error('Failed to save topic order');
            });
        }

    </script>
@endsection