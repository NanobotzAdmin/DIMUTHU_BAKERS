<div id="modal-save" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity">
    </div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

            <div
                class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">

                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">


                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <div class="flex flex-col items-start gap-2">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-yellow-50 sm:mx-0 sm:h-10 sm:w-10">
                                        <i class="bi bi-save text-[#D4A017] text-xl"></i>
                                    </div>
                                    <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center gap-2"
                                        id="modal-title">
                                        Save Schedule
                                    </h3>
                                </div>
                                <p class="text-sm text-gray-500 mb-4">
                                    Save your production schedule. Choose to update the existing schedule or
                                    create a new version.
                                </p>

                            </div>
                            <div class="mt-2">

                                <div class="space-y-4">

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Save
                                            Action</label>
                                        <div class="relative">
                                            <select id="save-action-select"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm border px-3 py-2">
                                                <option value="update" data-icon="bi-save">Update Current
                                                    (Overwrite)</option>
                                                <option value="new-version" data-icon="bi-file-text">Save as New
                                                    Version (Keep history)</option>
                                                <option value="new-schedule" data-icon="bi-folder-plus">Save as
                                                    New Schedule (Create separate)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="schedule-name-container" class="hidden">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Schedule
                                            Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="save-schedule-name"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm border px-3 py-2"
                                            placeholder="e.g., Holiday Production, Weekend Batch">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Description
                                            (Optional)</label>
                                        <textarea id="save-description" rows="3"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm border px-3 py-2 resize-none"
                                            placeholder="Add notes about this schedule version..."></textarea>
                                    </div>

                                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 space-y-1">
                                        <div class="flex items-center gap-2 text-sm font-medium text-blue-900">
                                            <i class="bi bi-clock"></i>
                                            Save Information
                                        </div>
                                        <div class="text-xs text-blue-700 space-y-1 pl-6">
                                            <div class="flex items-center gap-2">
                                                <span>Saved: <span id="current-date-display"></span></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span id="action-summary-text">Updating existing schedule</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button"
                        class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
                        id="btn-confirm-save">
                        <i class="bi bi-save mr-2"></i>
                        <span id="btn-save-text">Save</span>
                    </button>

                    <button type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                        data-close-modal="save">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Set initial date
        const now = new Date();
        $('#current-date-display').text(now.toLocaleString());

        // Handle Select Change
        $('#save-action-select').on('change', function () {
            const action = $(this).val();
            const btnTextSpan = $('#btn-save-text');
            const summaryText = $('#action-summary-text');
            const nameContainer = $('#schedule-name-container');
            const saveBtn = $('#btn-confirm-save');

            // Reset UI states
            nameContainer.addClass('hidden');
            saveBtn.prop('disabled', false);

            if (action === 'update') {
                btnTextSpan.text('Save');
                summaryText.text('Updating existing schedule');
            }
            else if (action === 'new-version') {
                btnTextSpan.text('Save Version');
                summaryText.text('Creating version v1.' + (new Date().getTime() % 100)); // Simulating version logic
            }
            else if (action === 'new-schedule') {
                btnTextSpan.text('Create Schedule');
                summaryText.text('Creating new separate schedule');
                nameContainer.removeClass('hidden'); // Show input

                // Validate empty input immediately
                if ($('#save-schedule-name').val().trim() === '') {
                    saveBtn.prop('disabled', true);
                }
            }
        });

        // Validation logic for New Schedule Name
        $('#save-schedule-name').on('input', function () {
            const action = $('#save-action-select').val();
            if (action === 'new-schedule') {
                const isValid = $(this).val().trim() !== '';
                $('#btn-confirm-save').prop('disabled', !isValid);
            }
        });

        // Handle Close Modal (Standard logic)
        $('[data-close-modal="save"]').on('click', function () {
            $('#modal-save').addClass('hidden');
        });

        // Optional: Handle Confirm Click
        $('#btn-confirm-save').on('click', function () {
            const data = {
                action: $('#save-action-select').val(),
                name: $('#save-schedule-name').val(),
                description: $('#save-description').val()
            };
            console.log("Saving:", data);
            // Add your AJAX call here
            $('#modal-save').addClass('hidden');
        });
    });
</script>