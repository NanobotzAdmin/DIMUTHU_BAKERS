<!-- Publish Schedule Modal -->
<div id="modal-publish" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-publish-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
        id="modal-publish-backdrop"></div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-[550px]">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2 mb-1">
                        <i class="bi bi-send text-[#D4A017] text-xl"></i>
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-publish-title">Publish Schedule</h3>
                    </div>
                    <p class="text-sm text-gray-500">
                        Publish this schedule to make it visible to production staff and activate it in the Kitchen
                        Production view.
                    </p>
                </div>

                <!-- Content -->
                <div class="px-6 py-4 space-y-4">

                    <!-- Schedule Summary -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-3">
                        <h4 class="font-semibold text-sm text-gray-900">Schedule Summary</h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <div class="text-gray-500 text-xs mb-1">Schedule Name</div>
                                <div class="font-medium text-gray-900" id="publish-schedule-name">--</div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs mb-1">Date Range</div>
                                <div class="font-medium text-gray-900" id="publish-date-range">--</div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs mb-1">Total Tasks</div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-file-text text-gray-400"></i>
                                    <span class="font-medium text-gray-900"><span id="publish-task-count">0</span>
                                        tasks</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-gray-500 text-xs mb-1">Conflicts</div>
                                <div class="flex items-center gap-2" id="publish-conflict-status">
                                    <!-- Injected via JS -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Conflict Warning (Toggleable) -->
                    <div id="publish-conflict-warning"
                        class="hidden bg-red-50 border-2 border-red-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle text-red-600 mt-0.5 text-lg"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm text-red-900 mb-1">
                                    Scheduling Conflicts Detected
                                </h4>
                                <p class="text-sm text-red-700 mb-3">
                                    This schedule contains <span id="publish-conflict-count-text">0</span> conflict(s).
                                    Publishing with conflicts may cause production issues.
                                </p>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="confirm-conflicts"
                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded cursor-pointer">
                                    <label for="confirm-conflicts"
                                        class="text-sm font-medium text-red-900 cursor-pointer select-none">
                                        I understand and want to publish anyway
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Publish Notes -->
                    <div class="space-y-2">
                        <label for="publish-notes" class="block text-sm font-medium text-gray-700">Publish Notes
                            (Optional)</label>
                        <textarea id="publish-notes" rows="3"
                            class="block w-full rounded-md border-gray-300 border shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2 resize-none"
                            placeholder="Add notes for staff about this schedule (e.g., 'Peak holiday orders - all hands on deck')"></textarea>
                    </div>

                    <!-- Notification Options -->
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="notify-staff" checked
                                class="h-4 w-4 text-[#D4A017] focus:ring-[#D4A017] border-gray-300 rounded mt-1 cursor-pointer">
                            <div class="flex-1">
                                <label for="notify-staff"
                                    class="text-sm font-medium cursor-pointer flex items-center gap-2 select-none">
                                    <i class="bi bi-people"></i>
                                    Notify staff via SMS/Email
                                </label>
                                <p class="text-xs text-gray-500 mt-1">
                                    Send notification to all assigned staff members about schedule updates
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- What Happens Next -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-2">
                        <h4 class="font-semibold text-sm text-blue-900 flex items-center gap-2">
                            <i class="bi bi-check-circle"></i>
                            What Happens After Publishing
                        </h4>
                        <ul class="text-xs text-blue-700 space-y-1.5 ml-6 list-disc">
                            <li>Schedule status changes from "Draft" to "Published"</li>
                            <li>Tasks become visible in Kitchen Production view</li>
                            <li>Staff receive notifications (if enabled)</li>
                            <li>Schedule is locked for editing (create new version to modify)</li>
                            <li>Production tracking begins automatically</li>
                        </ul>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-lg">
                    <button type="button" id="btn-confirm-publish"
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-[#D4A017] px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-send mr-2"></i> Publish Schedule
                    </button>
                    <button type="button" data-close-modal="publish"
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-[#D4A017] focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // --- Configuration ---
        const $modal = $('#modal-publish');
        const $btnPublish = $('#btn-confirm-publish');
        const $checkConfirm = $('#confirm-conflicts');

        // --- State Management ---
        let currentConflicts = 0;

        // --- Functions ---

        function openPublishModal() {
            // 1. Mock Data Loading (Replace with real data later)
            const scheduleName = "Main Schedule - Jan 2026";
            const dateRange = "Jan 1 - Jan 3, 2026";
            const taskCount = 45;
            // set random conflict count for demo or 0
            currentConflicts = 2; // Simulate conflicts for testing the warning logic

            // 2. Populate UI
            $('#publish-schedule-name').text(scheduleName);
            $('#publish-date-range').text(dateRange);
            $('#publish-task-count').text(taskCount);

            // 3. Handle Conflicts UI
            renderConflictStatus();

            // 4. Reset Form
            $('#publish-notes').val('');
            $('#notify-staff').prop('checked', true);
            $checkConfirm.prop('checked', false);

            $modal.removeClass('hidden');
        }

        function renderConflictStatus() {
            const $statusDiv = $('#publish-conflict-status');
            const $warningBox = $('#publish-conflict-warning');

            if (currentConflicts === 0) {
                // No Conflicts
                $statusDiv.html(`
                    <i class="bi bi-check-circle text-green-600"></i>
                    <span class="font-medium text-green-600">No conflicts</span>
                `);
                $warningBox.addClass('hidden');
                $btnPublish.prop('disabled', false);
            } else {
                // Conflicts Found
                $statusDiv.html(`
                    <i class="bi bi-exclamation-triangle text-red-600"></i>
                    <span class="font-medium text-red-600">${currentConflicts} conflicts</span>
                `);
                $('#publish-conflict-count-text').text(currentConflicts);
                $warningBox.removeClass('hidden');

                // Disable button initially until confirmed
                $btnPublish.prop('disabled', true);
            }
        }

        // --- Event Listeners ---

        // Trigger open (Generic trigger from timeline.blade.php)
        $('[data-modal="publish"]').on('click', function (e) {
            e.preventDefault();
            openPublishModal();
        });

        // Close
        $('[data-close-modal="publish"]').on('click', function () {
            $modal.addClass('hidden');
        });

        // Confirm Conflicts Toggle
        $checkConfirm.on('change', function () {
            const isConfirmed = $(this).is(':checked');
            $btnPublish.prop('disabled', currentConflicts > 0 && !isConfirmed);
        });

        // Publish Action
        $btnPublish.on('click', function () {
            const data = {
                notes: $('#publish-notes').val(),
                notifyStaff: $('#notify-staff').is(':checked'),
                forcePublish: currentConflicts > 0
            };

            // Log for verification
            console.log("Publishing Schedule:", data);

            // Mock Success
            alert(`Schedule Published!\nNotes: ${data.notes}\nNotify Staff: ${data.notifyStaff}`);
            $modal.addClass('hidden');
        });

        // Close on Backdrop Click
        $('#modal-publish-backdrop').on('click', function () {
            $modal.addClass('hidden');
        });
    });
</script>