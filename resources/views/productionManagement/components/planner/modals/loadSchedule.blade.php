<!-- Load Schedule Bottom Sheet Modal -->
<div id="modal-load" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-load-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity opacity-0" id="modal-load-backdrop">
    </div>

    <!-- Bottom Sheet Container -->
    <div class="fixed inset-x-0 bottom-0 z-50 w-full transform transition-transform translate-y-full duration-300 ease-in-out"
        id="modal-load-panel">
        <div class="bg-white rounded-t-2xl shadow-2xl w-full h-[85vh] flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="bi bi-folder2-open text-[#D4A017] text-xl"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-load-title">Load Schedule</h3>
                        <p class="text-xs text-gray-500">Select a saved schedule to load. All unsaved changes will be lost.</p>
                    </div>
                </div>
                <button class="p-2 hover:bg-gray-100 rounded-full text-gray-400 hover:text-gray-600 transition-colors"
                    data-close-modal="load">
                    <i class="bi bi-x-lg text-lg"></i>
                </button>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-hidden flex flex-col">
                
                <!-- Search -->
                <div class="px-6 py-4 border-b border-gray-50">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="load-schedule-search" 
                            class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4A017]/50 focus:border-[#D4A017]"
                            placeholder="Search schedules...">
                    </div>
                </div>

                <!-- Main List -->
                <div class="flex-1 px-6 py-2 overflow-y-auto bg-white" id="load-schedule-list-container">
                    <!-- Items injected via JS -->
                    <div class="text-center py-12 text-gray-500">
                        <i class="bi bi-arrow-clockwise animate-spin text-2xl"></i>
                        <p class="mt-2 text-sm">Loading schedules...</p>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="p-4 border-t border-gray-100 bg-white flex justify-end gap-3 rounded-b-xl pb-6">
                <button class="px-4 py-2 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 text-sm transition-colors"
                    data-close-modal="load">
                    Cancel
                </button>
                <button id="btn-load-selected" disabled
                    class="flex items-center gap-2 px-5 py-2 rounded-lg bg-[#D4A017] hover:bg-[#B8860B] text-white font-medium text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-folder2-open"></i>
                    Load Schedule
                </button>
            </div>
        </div>
    </div>

    <!-- Details/Delete Confirmation Overlay (Nested Modal) -->
    <div id="modal-delete-confirm" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/20 backdrop-blur-[1px]"></div>
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md relative z-10 p-6 transform transition-all scale-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Schedule</h3>
            <p class="text-sm text-gray-600 mb-6">Are you sure you want to delete this schedule? This action cannot be undone.</p>
            <div class="flex justify-end gap-3">
                <button id="btn-cancel-delete" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-md">Cancel</button>
                <button id="btn-confirm-delete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $modal = $('#modal-load');
        const $backdrop = $('#modal-load-backdrop');
        const $panel = $('#modal-load-panel');
        const $listContainer = $('#load-schedule-list-container');
        const $searchInput = $('#load-schedule-search');
        const $loadBtn = $('#btn-load-selected');

        // State
        let schedules = [];
        let selectedId = null;
        let deleteId = null;

        // Mock Data Initialization
        function initData() {
            // Replicating user's mock data
            schedules = [
                {
                    id: 'sched-1',
                    name: 'Main Schedule',
                    version: 'v1.5',
                    description: 'Current production schedule for January 2026',
                    savedAt: new Date('2026-01-01T09:30:00'),
                    taskCount: 7,
                    conflictCount: 1,
                    status: 'draft',
                },
                {
                    id: 'sched-2',
                    name: 'Main Schedule',
                    version: 'v1.4',
                    description: 'Previous version before oven capacity adjustments',
                    savedAt: new Date('2025-12-31T16:45:00'),
                    taskCount: 7,
                    conflictCount: 3,
                    status: 'archived',
                },
                {
                    id: 'sched-3',
                    name: 'Holiday Production',
                    version: 'v1.0',
                    description: 'Special schedule for New Year\'s Day orders',
                    savedAt: new Date('2025-12-30T14:20:00'),
                    taskCount: 12,
                    conflictCount: 0,
                    status: 'published',
                },
                {
                    id: 'sched-4',
                    name: 'Weekend Batch',
                    version: 'v2.1',
                    description: 'Weekend production with reduced staff',
                    savedAt: new Date('2025-12-28T11:00:00'),
                    taskCount: 5,
                    conflictCount: 0,
                    status: 'published',
                },
                {
                    id: 'sched-5',
                    name: 'Main Schedule',
                    version: 'v1.3',
                    description: 'Backup before major changes',
                    savedAt: new Date('2025-12-27T10:15:00'),
                    taskCount: 6,
                    conflictCount: 2,
                    status: 'archived',
                },
            ];
            renderList();
        }

        // Render List
        function renderList() {
            const query = $searchInput.val().toLowerCase();
            const filtered = schedules.filter(s => 
                s.name.toLowerCase().includes(query) || 
                s.description.toLowerCase().includes(query) ||
                s.version.toLowerCase().includes(query)
            );

            $listContainer.empty();

            if (filtered.length === 0) {
                $listContainer.html(`
                    <div class="text-center py-12 text-gray-500">
                        <i class="bi bi-file-text text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-sm">No schedules found</p>
                    </div>
                `);
                return;
            }

            filtered.forEach(schedule => {
                const isSelected = selectedId === schedule.id;
                
                // Status Badge Logic
                let badgeClass = '';
                let badgeText = '';
                switch(schedule.status) {
                    case 'draft': badgeClass = 'bg-amber-50 text-amber-700 border-amber-300'; badgeText = 'Draft'; break;
                    case 'published': badgeClass = 'bg-green-50 text-green-700 border-green-300'; badgeText = 'Published'; break;
                    case 'archived': badgeClass = 'bg-gray-50 text-gray-600 border-gray-300'; badgeText = 'Archived'; break;
                }

                // Conflict/Status logic
                let conflictHtml = '';
                if (schedule.conflictCount > 0) {
                    conflictHtml = `<div class="flex items-center gap-1 text-red-600"><i class="bi bi-exclamation-circle"></i> ${schedule.conflictCount} conflicts</div>`;
                } else {
                    conflictHtml = `<div class="flex items-center gap-1 text-green-600"><i class="bi bi-check-circle"></i> No conflicts</div>`;
                }

                const card = $(`
                    <div class="w-full text-left p-4 rounded-lg border-2 transition-all cursor-pointer mb-2
                        ${isSelected ? 'border-[#D4A017] bg-[#D4A017]/5' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'}"
                        data-id="${schedule.id}">
                        
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <!-- Header -->
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="font-semibold text-gray-900 truncate">${schedule.name}</h4>
                                    <span class="px-2 py-0.5 rounded text-xs border border-gray-200 text-gray-600 bg-white">${schedule.version}</span>
                                    <span class="px-2 py-0.5 rounded text-xs border ${badgeClass}">${badgeText}</span>
                                </div>

                                <!-- Description -->
                                <p class="text-sm text-gray-600 mb-2 truncate">${schedule.description}</p>

                                <!-- Metadata -->
                                <div class="flex items-center gap-4 text-xs text-gray-500 flex-wrap">
                                    <div class="flex items-center gap-1"><i class="bi bi-calendar"></i> ${schedule.savedAt.toLocaleDateString()}</div>
                                    <div class="flex items-center gap-1"><i class="bi bi-clock"></i> ${schedule.savedAt.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                                    <div class="flex items-center gap-1"><i class="bi bi-list-task"></i> ${schedule.taskCount} tasks</div>
                                    ${conflictHtml}
                                </div>
                            </div>

                            <!-- Actions -->
                            <button class="btn-delete-schedule p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition-colors"
                                data-id="${schedule.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        ${isSelected ? `
                        <div class="mt-3 pt-3 border-t border-[#D4A017]/20">
                            <div class="flex items-center gap-2 text-sm text-[#D4A017]">
                                <i class="bi bi-check-circle-fill"></i>
                                <span class="font-medium">Selected - Click "Load" to open this schedule</span>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                `);

                // Click selection
                card.on('click', function(e) {
                    if($(e.target).closest('.btn-delete-schedule').length) return;
                    selectedId = schedule.id;
                    $loadBtn.prop('disabled', false);
                    renderList(); // Re-render to update UI
                });

                // Delete click
                card.find('.btn-delete-schedule').on('click', function(e) {
                    e.stopPropagation();
                    deleteId = schedule.id;
                    $('#modal-delete-confirm').removeClass('hidden');
                });

                $listContainer.append(card);
            });
        }

        // Search Input
        $searchInput.on('input', function() {
            renderList();
        });

        // Delete Confirm Logic
        $('#btn-cancel-delete').on('click', function() {
            $('#modal-delete-confirm').addClass('hidden');
            deleteId = null;
        });

        $('#btn-confirm-delete').on('click', function() {
            if (deleteId) {
                schedules = schedules.filter(s => s.id !== deleteId);
                if (selectedId === deleteId) {
                    selectedId = null;
                    $loadBtn.prop('disabled', true);
                }
                renderList();
                $('#modal-delete-confirm').addClass('hidden');
                deleteId = null;
            }
        });

        // Load Button
        $loadBtn.on('click', function() {
            if (selectedId) {
                const schedule = schedules.find(s => s.id === selectedId);
                console.log("Loading Schedule:", schedule);
                // Here you would trigger the actual load logic
                // window.TimelinePlanner.loadSchedule(schedule); 
                closeLoadModal();
            }
        });


        // --- Toggle Logic (Matches Previous Fix) ---
        $(document).on('click', '[data-trigger-load]', function (e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Re-init data if empty (simulate fetch)
            if(schedules.length === 0) initData();

            $modal.removeClass('hidden');
            
            // Standardize Animation: Use Tailwind classes directly
            setTimeout(() => {
                // Backdrop fade in
                $backdrop.removeClass('opacity-0').addClass('opacity-100');
                
                // Panel slide up
                $panel.removeClass('translate-y-full').addClass('translate-y-0');
            }, 10);
        });

        function closeLoadModal() {
            // Backdrop fade out
            $backdrop.removeClass('opacity-100').addClass('opacity-0');
            
            // Panel slide down
            $panel.removeClass('translate-y-0').addClass('translate-y-full');

            setTimeout(() => {
                $modal.addClass('hidden');
                // Optional: reset selection
                // selectedId = null;
                // $loadBtn.prop('disabled', true);
                // renderList(); 
            }, 300);
        }

        $('[data-close-modal="load"]').on('click', closeLoadModal);
        $backdrop.on('click', closeLoadModal);
    });
</script>