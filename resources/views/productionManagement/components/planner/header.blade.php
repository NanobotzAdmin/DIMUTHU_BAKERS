<div class="bg-gray-900 border-b border-gray-700 flex-shrink-0 relative z-30 w-full">
    <div class="px-6 py-3 flex items-center justify-between">
        <!-- Left: Logo & Schedule Selector -->
        <div class="flex items-center gap-4">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-[#D4A017] to-[#B8860B] rounded-lg flex items-center justify-center shadow-lg shadow-amber-900/20">
                    <i class="bi bi-calendar3 text-white text-lg"></i>
                </div>
                <div assss="flex items-center gap-2">
                    <span class="text-white text-sm tracking-wide">BakeryMate</span>
                    <span class="text-gray-500">|</span>
                    <span class="text-white font-semibold tracking-tight">Advanced Planner</span>
                    <span
                        class="ml-2 text-[#D4A017] border border-[#D4A017] bg-[#D4A017]/10 text-[10px] font-bold px-1.5 py-0.5 rounded">
                        BETA
                    </span>
                </div>
            </div>

            <!-- Schedule Selector -->
            <div class="ml-6 relative group">
                <select
                    class="appearance-none w-[200px] bg-gray-800 border border-gray-700 text-white h-9 pl-3 pr-8 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-[#D4A017] focus:border-[#D4A017] transition-colors cursor-pointer hover:bg-gray-750">
                    <option value="main-schedule">Main Schedule</option>
                    <option value="backup">Backup Schedule</option>
                    <option value="new">+ New Schedule</option>
                </select>
                <div class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <i class="bi bi-chevron-down text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Center: Main Tabs -->
        <div class="flex items-center gap-1" id="planner-tabs">
            <button data-view="timeline"
                class="btn-switch-view px-4 py-2 text-sm font-medium transition-all relative flex items-center gap-2 group text-white">
                <i class="bi bi-calendar3 text-base"></i>
                <span>Timeline</span>
                <div
                    class="active-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-[#D4A017] shadow-[0_0_8px_rgba(212,160,23,0.5)]">
                </div>
            </button>
            <button data-view="analytics"
                class="btn-switch-view px-4 py-2 text-sm font-medium transition-all relative flex items-center gap-2 group text-gray-400 hover:text-gray-200">
                <i class="bi bi-bar-chart text-base"></i>
                <span>Analytics</span>
                <div
                    class="active-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-[#D4A017] shadow-[0_0_8px_rgba(212,160,23,0.5)] hidden">
                </div>
            </button>
            <button data-view="resources"
                class="btn-switch-view px-4 py-2 text-sm font-medium transition-all relative flex items-center gap-2 group text-gray-400 hover:text-gray-200">
                <i class="bi bi-people text-base"></i>
                <span>Resources</span>
                <div
                    class="active-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-[#D4A017] shadow-[0_0_8px_rgba(212,160,23,0.5)] hidden">
                </div>
            </button>
            <button data-view="settings"
                class="btn-switch-view px-4 py-2 text-sm font-medium transition-all relative flex items-center gap-2 group text-gray-400 hover:text-gray-200">
                <i class="bi bi-gear text-base"></i>
                <span>Settings</span>
                <div
                    class="active-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-[#D4A017] shadow-[0_0_8px_rgba(212,160,23,0.5)] hidden">
                </div>
            </button>
            <button data-view="kitchen"
                class="btn-switch-view px-4 py-2 text-sm font-medium transition-all relative flex items-center gap-2 group text-gray-400 hover:text-gray-200">
                <i class="bi bi-display text-base"></i>
                <span>Kitchen</span>
                <div
                    class="active-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-[#D4A017] shadow-[0_0_8px_rgba(212,160,23,0.5)] hidden">
                </div>
            </button>
        </div>

        <!-- Right: View Mode, Save, Exit -->
        <div class="flex items-center gap-3">
            <!-- View Mode Selector -->
            <div class="flex items-center gap-1 bg-gray-800 rounded-lg p-1 border border-gray-700"
                id="view-mode-selector">
                <button data-mode="day"
                    class="btn-view-mode px-3 py-1.5 text-xs font-medium rounded transition-all text-gray-300 hover:text-white hover:bg-gray-700">Day</button>
                <button data-mode="3-day"
                    class="btn-view-mode px-3 py-1.5 text-xs font-medium rounded transition-all bg-[#D4A017] text-white shadow-sm">3-Day</button>
                <button data-mode="week"
                    class="btn-view-mode px-3 py-1.5 text-xs font-medium rounded transition-all text-gray-300 hover:text-white hover:bg-gray-700">Week</button>
            </div>

            <!-- Auto-save indicator -->
            <div class="text-xs flex items-center gap-1.5 min-w-[80px] justify-end mr-2">
                <div id="status-unsaved" class="hidden flex items-center gap-1.5 text-amber-500 animate-pulse">
                    <i class="bi bi-clock text-xs"></i>
                    <span>Unsaved</span>
                </div>
                <div id="status-saved" class="flex items-center gap-1.5 text-green-500">
                    <i class="bi bi-check-circle text-xs"></i>
                    <span>Saved</span>
                </div>
            </div>

            <!-- Save Button -->
            <button id="btn-save" disabled
                class="h-9 px-4 bg-[#D4A017] text-white text-sm font-medium rounded-md flex items-center gap-2 transition-all opacity-50 cursor-not-allowed">
                <i class="bi bi-save"></i>
                Save
            </button>

            <!-- Exit Button -->
            <button id="btn-exit"
                class="h-9 px-4 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md text-sm font-medium flex items-center gap-2 transition-colors">
                <i class="bi bi-x-lg"></i>
                Exit
            </button>
        </div>
    </div>

    <!-- Exit Confirmation Dialog -->
    <div id="exit-confirmation-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" id="modal-backdrop"></div>

        <!-- Modal -->
        <div
            class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-gray-200 z-50">
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="bi bi-exclamation-triangle text-amber-600 text-lg"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Unsaved Changes</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                You have unsaved changes to your schedule. Would you like to save before leaving? All
                                pending changes will be lost if you don't save.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" id="btn-modal-save-exit"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017] sm:ml-3 sm:w-auto sm:text-sm">
                    Save & Exit
                </button>
                <button type="button" id="btn-modal-discard"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Discard Changes
                </button>
                <button type="button" id="btn-modal-cancel"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>