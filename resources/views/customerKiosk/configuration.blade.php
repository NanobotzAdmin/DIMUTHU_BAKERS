@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto" x-data="kioskConfigManager.init()">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl text-gray-900 mb-2">Customer Kiosk Configuration</h1>
                    <p class="text-gray-600">
                        Configure and calibrate your self-service kiosk terminal
                    </p>
                </div>
                <button onclick="kioskConfigManager.handleLaunchKiosk()" id="btn-launch-header" disabled
                    class="flex items-center gap-3 px-6 py-4 rounded-xl transition-all transform active:scale-95 shadow-lg bg-gray-300 text-gray-500 cursor-not-allowed">
                    <i class="bi bi-box-arrow-up-right text-lg"></i>
                    <span class="text-lg">Launch Kiosk Mode</span>
                </button>
            </div>

            <!-- Status Banner -->
            <div id="status-banner" class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                <i class="bi bi-exclamation-circle text-amber-600 flex-shrink-0 mt-0.5 text-xl"></i>
                <div>
                    <p class="text-amber-900 mb-1 font-medium">Calibration Required</p>
                    <p class="text-sm text-amber-700">
                        Please complete all calibration steps before launching the kiosk.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex gap-2 mb-6 border-b border-gray-200">
            <button onclick="kioskConfigManager.setTab('overview')" id="tab-overview"
                class="px-6 py-3 transition-colors border-b-2 border-amber-500 text-amber-600 font-medium">
                <i class="bi bi-display w-5 h-5 inline mr-2"></i>
                Overview
            </button>
            <button onclick="kioskConfigManager.setTab('calibrate')" id="tab-calibrate"
                class="px-6 py-3 transition-colors border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-medium">
                <i class="bi bi-gear w-5 h-5 inline mr-2"></i>
                Calibration
            </button>
            <button onclick="kioskConfigManager.setTab('settings')" id="tab-settings"
                class="px-6 py-3 transition-colors border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-medium">
                <i class="bi bi-phone w-5 h-5 inline mr-2"></i>
                Settings
            </button>
            <button onclick="kioskConfigManager.setTab('video-ads')" id="tab-video-ads"
                class="px-6 py-3 transition-colors border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-medium">
                <i class="bi bi-camera-video w-5 h-5 inline mr-2"></i>
                Video Ads
            </button>
        </div>

        <!-- Content Sections -->

        <!-- Overview Tab -->
        <div id="content-overview" class="space-y-6">
            <!-- Progress Card -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Setup Progress</h2>
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Calibration Status</span>
                        <span id="progress-text">0 of 5 completed</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div id="progress-bar"
                            class="bg-gradient-to-r from-amber-500 to-orange-600 h-3 rounded-full transition-all duration-500"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Current Settings Summary -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Current Configuration</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-geo-alt text-gray-400 mt-0.5 text-lg"></i>
                        <div>
                            <p class="text-sm text-gray-600">Terminal ID</p>
                            <p class="text-gray-900 font-medium" id="summary-terminal-id">KIOSK-001</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-cart text-gray-400 mt-0.5 text-lg"></i>
                        <div>
                            <p class="text-sm text-gray-600">Outlet</p>
                            <p class="text-gray-900 font-medium" id="summary-outlet">Main Bakery</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-clock text-gray-400 mt-0.5 text-lg"></i>
                        <div>
                            <p class="text-sm text-gray-600">Idle Timeout</p>
                            <p class="text-gray-900 font-medium"><span id="summary-idle">2</span> minutes</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="bi bi-currency-dollar text-gray-400 mt-0.5 text-lg"></i>
                        <div>
                            <p class="text-sm text-gray-600">Tax Rate</p>
                            <p class="text-gray-900 font-medium"><span id="summary-tax">8</span>%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-2 gap-4">
                <button onclick="kioskConfigManager.handleLaunchKiosk()" id="btn-launch-overview" disabled
                    class="p-6 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 opacity-50 cursor-not-allowed transition-all text-center group hover:bg-gray-100">
                    <i
                        class="bi bi-play-circle text-3xl mx-auto mb-2 text-gray-400 block group-hover:scale-110 transition-transform"></i>
                    <p class="text-gray-900 mb-1 font-medium">Launch Kiosk</p>
                    <p class="text-sm text-gray-600">Complete calibration first</p>
                </button>

                <button onclick="window.location.reload()"
                    class="p-6 rounded-xl border-2 border-dashed border-blue-300 bg-blue-50 hover:bg-blue-100 transition-all text-center group cursor-pointer">
                    <i
                        class="bi bi-arrow-repeat text-3xl text-blue-600 mx-auto mb-2 block group-hover:rotate-180 transition-transform"></i>
                    <p class="text-gray-900 mb-1 font-medium">Reset Configuration</p>
                    <p class="text-sm text-gray-600">Start fresh setup</p>
                </button>
            </div>
        </div>

        <!-- Calibration Tab -->
        <div id="content-calibrate" class="grid grid-cols-1 gap-4 hidden">
            <!-- Items generated by JS -->
        </div>

        <!-- Settings Tab -->
        <div id="content-settings" class="space-y-6 hidden">
            <!-- Terminal Settings -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Terminal Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Terminal ID</label>
                        <input type="text" id="setting-terminal-id" value="KIOSK-001"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Outlet Name</label>
                        <input type="text" id="setting-outlet-name" value="Main Bakery"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            <!-- Timing Settings -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Timing Configuration</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Idle Timeout (minutes)</label>
                        <input type="number" id="setting-idle-timeout" value="2" min="1" max="10"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <p class="text-xs text-gray-500 mt-1">Auto-reset to welcome screen after inactivity</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Auto-Return Timeout (seconds)</label>
                        <input type="number" id="setting-auto-return" value="30" min="10" max="60"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <p class="text-xs text-gray-500 mt-1">Return to welcome after order confirmation</p>
                    </div>
                </div>
            </div>

            <!-- Order Settings -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Order Configuration</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Tax Rate (%)</label>
                        <input type="number" id="setting-tax-rate" value="8" min="0" max="100" step="0.1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="setting-dine-in" checked
                                class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-700">Enable Dine-In</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="setting-takeaway" checked
                                class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-gray-700">Enable Takeaway</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Customer Information</h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="setting-cust-info" checked onchange="kioskConfigManager.toggleCustReq()"
                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-gray-700 block">Enable Customer Information</span>
                            <span class="text-sm text-gray-500">Allow customers to provide name and phone</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer ml-8" id="container-cust-req">
                        <input type="checkbox" id="setting-cust-req"
                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-gray-700 block">Require Customer Information</span>
                            <span class="text-sm text-gray-500">Make name and phone mandatory</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Hardware Settings -->
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                <h2 class="text-xl text-gray-900 mb-4 font-semibold">Hardware</h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="setting-printer" checked
                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-gray-700 block">Receipt Printer</span>
                            <span class="text-sm text-gray-500">Enable receipt printing</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" id="setting-offline"
                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-gray-700 block">Offline Mode</span>
                            <span class="text-sm text-gray-500">Queue orders when internet is unavailable</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button onclick="kioskConfigManager.saveSettings()"
                    class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-lg transition-all shadow-md flex items-center gap-2 font-medium">
                    <i class="bi bi-check-lg text-xl"></i>
                    <span>Save Settings</span>
                </button>
            </div>
        </div>

        <!-- Video Ads Tab -->
        <div id="content-video-ads" class="space-y-6 hidden">
            <!-- Video Manager Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl text-gray-900 font-semibold">Video Ad Management</h2>
                    <p class="text-gray-600">Manage promotional content for kiosk idle screen</p>
                </div>
                <button onclick="videoAdManager.openModal()"
                    class="flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors font-medium">
                    <i class="bi bi-plus-lg"></i>
                    Add Video
                </button>
            </div>

            <!-- Video Tabs -->
            <div class="flex gap-2 border-b border-gray-200">
                <button onclick="videoAdManager.setSubTab('videos')" id="subtab-videos"
                    class="px-4 py-2 border-b-2 transition-colors border-amber-500 text-amber-600 font-medium">
                    <i class="bi bi-camera-video w-4 h-4 inline mr-2"></i>
                    Videos (<span id="video-count">0</span>)
                </button>
                <button onclick="videoAdManager.setSubTab('settings')" id="subtab-settings"
                    class="px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 font-medium">
                    <i class="bi bi-gear w-4 h-4 inline mr-2"></i>
                    Settings
                </button>
                <button onclick="videoAdManager.setSubTab('analytics')" id="subtab-analytics"
                    class="px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 font-medium">
                    <i class="bi bi-bar-chart w-4 h-4 inline mr-2"></i>
                    Analytics
                </button>
            </div>

            <!-- Video List View -->
            <div id="subcontent-videos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Populated by JS -->
            </div>

            <!-- Video Settings View -->
            <div id="subcontent-settings" class="space-y-6 hidden">
                <!-- Settings Form -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <label class="flex items-center gap-3 cursor-pointer mb-6">
                        <input type="checkbox" id="video-setting-enabled"
                            class="w-5 h-5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                        <div>
                            <span class="text-lg text-gray-900 block font-medium">Enable Video Ads</span>
                            <span class="text-sm text-gray-600">Show promotional videos on kiosk idle screen</span>
                        </div>
                    </label>

                    <h3 class="text-lg text-gray-900 mb-4 font-medium">Playback Settings</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-2">Playback Mode</label>
                            <select id="video-setting-mode"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                                <option value="sequential">Sequential (by priority)</option>
                                <option value="random">Random</option>
                                <option value="weighted">Weighted Random (priority-based)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-2">
                                Default Volume: <span id="volume-display">50</span>%
                            </label>
                            <input type="range" id="video-setting-volume" min="0" max="100"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="video-setting-mute"
                                    class="w-4 h-4 rounded border-gray-300 text-amber-600">
                                <span class="text-sm text-gray-700">Mute by Default</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="video-setting-loop"
                                    class="w-4 h-4 rounded border-gray-300 text-amber-600">
                                <span class="text-sm text-gray-700">Loop Single Video</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="video-setting-controls"
                                    class="w-4 h-4 rounded border-gray-300 text-amber-600">
                                <span class="text-sm text-gray-700">Show Video Controls</span>
                            </label>

                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="video-setting-skip"
                                    class="w-4 h-4 rounded border-gray-300 text-amber-600">
                                <span class="text-sm text-gray-700">Show Skip Button</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-700 mb-2">
                                Auto-Skip After (seconds, 0 = disabled)
                            </label>
                            <input type="number" id="video-setting-autoskip" min="0" max="300"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        <button onclick="videoAdManager.saveSettings()"
                            class="mt-4 px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors font-medium">
                            Save Settings
                        </button>
                    </div>
                </div>
            </div>

            <!-- Analytics View -->
            <div id="subcontent-analytics" class="space-y-6 hidden">
                <!-- Stats Cards -->
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Total Videos</p>
                        <p class="text-3xl text-gray-900 font-bold" id="stat-total-videos">0</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Active Videos</p>
                        <p class="text-3xl text-green-600 font-bold" id="stat-active-videos">0</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Total Plays</p>
                        <p class="text-3xl text-blue-600 font-bold" id="stat-total-plays">0</p>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Completion Rate</p>
                        <p class="text-3xl text-amber-600 font-bold" id="stat-completion">0%</p>
                    </div>
                </div>

                <!-- Top Performing Videos -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg text-gray-900 mb-4 font-medium">Top Performing Videos</h3>
                    <div class="space-y-3" id="top-videos-list">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Add/Edit Video Modal -->
    <div id="video-modal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto shadow-2xl">
            <h2 class="text-2xl text-gray-900 mb-6 font-semibold" id="modal-title">Add New Video</h2>
            <input type="hidden" id="video-id">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">Title *</label>
                    <input type="text" id="video-title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Summer Special Promotion">
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Description</label>
                    <textarea id="video-desc" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Promotional video for summer specials..."></textarea>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">Type</label>
                    <select id="video-type"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="video">Video File (MP4, WebM)</option>
                        <option value="youtube">YouTube Video</option>
                        <option value="image">Image Slide</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2" id="video-url-label">Video URL *</label>
                    <input type="text" id="video-url"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="https://example.com/video.mp4">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Duration (seconds)</label>
                        <input type="number" id="video-duration" min="1" value="30"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-2">Priority (1-10)</label>
                        <input type="number" id="video-priority" min="1" max="10" value="5"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="video-active" checked class="w-4 h-4 rounded border-gray-300 text-amber-600">
                    <span class="text-sm text-gray-700">Active (show in rotation)</span>
                </label>
            </div>

            <div class="flex gap-3 mt-6">
                <button onclick="videoAdManager.submitModal()"
                    class="flex-1 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors font-medium">
                    Save Video
                </button>
                <button onclick="videoAdManager.closeModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    <!-- Toast Notification -->
    <div id="toast"
        class="fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-3 z-50">
        <i id="toast-icon" class="bi bi-check-circle-fill text-green-400"></i>
        <span id="toast-message">Operation successful</span>
    </div>

    <script>
        const kioskConfigManager = {
            currentTab: 'overview',
            calibrationStatus: {
                display: false,
                touch: false,
                network: false,
                printer: false,
                products: false
            },
            calibrationItems: [
                { id: 'display', icon: 'bi-display', title: 'Display Calibration', desc: 'Test screen brightness, colors, and orientation', color: 'purple' },
                { id: 'touch', icon: 'bi-phone', title: 'Touch Screen Test', desc: 'Calibrate touch sensitivity and accuracy', color: 'blue' },
                { id: 'network', icon: 'bi-wifi', title: 'Network Connection', desc: 'Verify internet connectivity and API access', color: 'green' },
                { id: 'printer', icon: 'bi-printer', title: 'Receipt Printer', desc: 'Test receipt printing functionality', color: 'orange' },
                { id: 'products', icon: 'bi-cart', title: 'Product Sync', desc: 'Load and verify product catalog', color: 'amber' }
            ],

            init() {
                this.loadSettings();
                this.renderCalibrationItems();
                this.updateUI();
                videoAdManager.init(); // Init video manager
            },

            loadSettings() {
                const saved = localStorage.getItem('kiosk-settings');
                if (saved) {
                    const settings = JSON.parse(saved);
                    document.getElementById('setting-terminal-id').value = settings.terminalId;
                    document.getElementById('setting-outlet-name').value = settings.outletName;
                    document.getElementById('setting-idle-timeout').value = settings.idleTimeout;
                    document.getElementById('setting-auto-return').value = settings.autoReturnTimeout;
                    document.getElementById('setting-tax-rate').value = settings.taxRate;
                    document.getElementById('setting-dine-in').checked = settings.enableDineIn;
                    document.getElementById('setting-takeaway').checked = settings.enableTakeaway;
                    document.getElementById('setting-cust-info').checked = settings.enableCustomerInfo;
                    document.getElementById('setting-cust-req').checked = settings.requireCustomerInfo;
                    document.getElementById('setting-printer').checked = settings.printerEnabled;
                    document.getElementById('setting-offline').checked = settings.offlineMode;

                    this.toggleCustReq();
                    this.updateSummary(settings);
                }
            },

            saveSettings() {
                const settings = {
                    terminalId: document.getElementById('setting-terminal-id').value,
                    outletName: document.getElementById('setting-outlet-name').value,
                    idleTimeout: parseInt(document.getElementById('setting-idle-timeout').value),
                    autoReturnTimeout: parseInt(document.getElementById('setting-auto-return').value),
                    taxRate: parseFloat(document.getElementById('setting-tax-rate').value),
                    enableDineIn: document.getElementById('setting-dine-in').checked,
                    enableTakeaway: document.getElementById('setting-takeaway').checked,
                    enableCustomerInfo: document.getElementById('setting-cust-info').checked,
                    requireCustomerInfo: document.getElementById('setting-cust-req').checked,
                    printerEnabled: document.getElementById('setting-printer').checked,
                    offlineMode: document.getElementById('setting-offline').checked
                };

                localStorage.setItem('kiosk-settings', JSON.stringify(settings));
                this.showToast('Settings saved successfully!');
                this.updateSummary(settings);
            },

            updateSummary(settings) {
                document.getElementById('summary-terminal-id').innerText = settings.terminalId;
                document.getElementById('summary-outlet').innerText = settings.outletName;
                document.getElementById('summary-idle').innerText = settings.idleTimeout;
                document.getElementById('summary-tax').innerText = settings.taxRate;
            },

            setTab(tab) {
                this.currentTab = tab;

                // Hide all contents
                ['overview', 'calibrate', 'settings', 'video-ads'].forEach(t => {
                    document.getElementById('content-' + t).classList.add('hidden');
                    const btn = document.getElementById('tab-' + t);
                    btn.classList.remove('border-amber-500', 'text-amber-600');
                    btn.classList.add('border-transparent', 'text-gray-600');
                });

                // Show active
                document.getElementById('content-' + tab).classList.remove('hidden');
                const activeBtn = document.getElementById('tab-' + tab);
                activeBtn.classList.remove('border-transparent', 'text-gray-600');
                activeBtn.classList.add('border-amber-500', 'text-amber-600');
            },

            renderCalibrationItems() {
                const container = document.getElementById('content-calibrate');
                container.innerHTML = '';

                this.calibrationItems.forEach(item => {
                    const isCalibrated = this.calibrationStatus[item.id];
                    const div = document.createElement('div');

                    // Colors
                    const colors = {
                        purple: 'bg-purple-100 text-purple-600',
                        blue: 'bg-blue-100 text-blue-600',
                        green: 'bg-green-100 text-green-600',
                        orange: 'bg-orange-100 text-orange-600',
                        amber: 'bg-amber-100 text-amber-600'
                    };
                    const colorClass = colors[item.color];

                    div.className = `bg-white rounded-xl shadow-md p-6 border-2 transition-all ${isCalibrated ? 'border-green-200 bg-green-50/30' : 'border-gray-200'}`;
                    div.innerHTML = `
                                 <div class="flex items-center justify-between">
                                    <div class="flex items-start gap-4 flex-1">
                                        <div class="w-12 h-12 rounded-xl ${colorClass} flex items-center justify-center flex-shrink-0">
                                            <i class="bi ${item.icon} text-xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg text-gray-900 mb-1 flex items-center gap-2 font-medium">
                                                ${item.title}
                                                ${isCalibrated ? '<i class="bi bi-check-lg text-green-600"></i>' : ''}
                                            </h3>
                                            <p class="text-gray-600 text-sm">${item.desc}</p>
                                        </div>
                                    </div>

                                    <button onclick="kioskConfigManager.runCalibration('${item.id}')" ${isCalibrated ? 'disabled' : ''}
                                        class="px-6 py-3 rounded-lg transition-all flex items-center gap-2 font-medium ${isCalibrated ? 'bg-green-500 text-white cursor-default' : 'bg-amber-500 hover:bg-amber-600 text-white'}">
                                        ${isCalibrated ? '<i class="bi bi-check-lg"></i><span>Calibrated</span>' : '<i class="bi bi-play-circle"></i><span>Run Test</span>'}
                                    </button>
                                </div>
                            `;
                    container.appendChild(div);
                });
            },

            async runCalibration(id) {
                this.showToast(`Running ${id} calibration...`, 'info');

                // Simulate delay
                const btn = event.currentTarget;
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<span class="animate-spin inline-block mr-2"><i class="bi bi-arrow-repeat"></i></span>Testing...';
                btn.disabled = true;

                await new Promise(r => setTimeout(r, 2000));

                this.calibrationStatus[id] = true;
                this.renderCalibrationItems();
                this.updateUI();
                this.showToast(`${id} calibration completed!`);
            },

            updateUI() {
                const completed = Object.values(this.calibrationStatus).filter(Boolean).length;
                const total = this.calibrationItems.length;
                const percent = (completed / total) * 100;
                const allDone = percent === 100;

                // Progress Bar
                document.getElementById('progress-bar').style.width = percent + '%';
                document.getElementById('progress-text').innerText = `${completed} of ${total} completed`;

                // Launch Buttons
                const btnHeader = document.getElementById('btn-launch-header');
                const btnOverview = document.getElementById('btn-launch-overview');

                if (allDone) {
                    const activeClasses = ['bg-gradient-to-r', 'from-green-500', 'to-emerald-600', 'hover:from-green-600', 'hover:to-emerald-700', 'text-white', 'cursor-pointer'];
                    const disabledClasses = ['bg-gray-300', 'text-gray-500', 'cursor-not-allowed', 'opacity-50'];

                    // Header Button
                    btnHeader.disabled = false;
                    btnHeader.classList.remove(...disabledClasses);
                    btnHeader.classList.add(...activeClasses);

                    // Overview Button
                    btnOverview.disabled = false;
                    btnOverview.classList.remove('border-gray-300', 'bg-gray-50', 'opacity-50', 'cursor-not-allowed');
                    btnOverview.classList.add('border-green-300', 'bg-green-50', 'hover:bg-green-100', 'cursor-pointer');
                    btnOverview.querySelector('i').classList.remove('text-gray-400');
                    btnOverview.querySelector('i').classList.add('text-green-600');
                    btnOverview.querySelector('p:nth-child(3)').innerText = "Ready to start";

                    // Hide Warning Banner
                    document.getElementById('status-banner')?.classList.add('hidden');
                }
            },

            handleLaunchKiosk() {
                const width = window.screen.width;
                const height = window.screen.height;
                this.showToast('Launching Kiosk Mode...', 'success');
                setTimeout(() => {
                    window.open('/', 'BakeryMate Kiosk', `width=${width},height=${height},fullscreen=yes,toolbar=no,menubar=no,location=no,status=no`);
                }, 500);
            },

            toggleCustReq() {
                const enabled = document.getElementById('setting-cust-info').checked;
                const container = document.getElementById('container-cust-req');
                if (enabled) {
                    container.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    container.classList.add('opacity-50', 'pointer-events-none');
                    document.getElementById('setting-cust-req').checked = false;
                }
            },

            showToast(msg, type = 'success') {
                const toast = document.getElementById('toast');
                document.getElementById('toast-message').innerText = msg;
                const icon = document.getElementById('toast-icon');

                if (type === 'info') {
                    icon.className = 'bi bi-info-circle-fill text-blue-400';
                } else {
                    icon.className = 'bi bi-check-circle-fill text-green-400';
                }

                toast.classList.remove('translate-y-20', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }
        };

        const videoAdManager = {
            videos: [],
            settings: {
                enabled: true,
                playbackMode: 'sequential',
                volume: 50,
                muteByDefault: false,
                loopSingleVideo: false,
                showControls: false,
                showSkipButton: false,
                skipButtonDelay: 0,
                autoSkipAfter: 0
            },

            init() {
                this.loadData();
                this.updateVideoList();
                this.updateAnalytics();
                this.bindInputs();
            },

            loadData() {
                const storedVideos = localStorage.getItem('kiosk-video-ads');
                if (storedVideos) {
                    this.videos = JSON.parse(storedVideos);
                } else {
                    // Dummy Data
                    this.videos = [
                        {
                            id: 'vid_1',
                            title: 'Morning Special Promo',
                            desc: '50% off on all croissants before 10 AM',
                            type: 'video',
                            url: 'https://example.com/promo.mp4',
                            duration: 15,
                            priority: 10,
                            isActive: true,
                            stats: { plays: 120, skips: 5 }
                        },
                        {
                            id: 'vid_2',
                            title: 'New Cake Collection',
                            desc: 'Showcase of our latest wedding cake designs',
                            type: 'image',
                            url: 'https://example.com/cakes.jpg',
                            duration: 10,
                            priority: 5,
                            isActive: true,
                            stats: { plays: 85, skips: 2 }
                        },
                        {
                            id: 'vid_3',
                            title: 'Coffee Brewing Process',
                            desc: 'Behind the scenes of our premium coffee',
                            type: 'youtube',
                            url: 'https://youtube.com/watch?v=12345',
                            duration: 60,
                            priority: 3,
                            isActive: false,
                            stats: { plays: 45, skips: 10 }
                        }
                    ];
                    this.saveVideos();
                }

                const storedSettings = localStorage.getItem('kiosk-video-settings');
                if (storedSettings) this.settings = JSON.parse(storedSettings);

                // Settings Inputs
                document.getElementById('video-setting-enabled').checked = this.settings.enabled;
                document.getElementById('video-setting-mode').value = this.settings.playbackMode;
                document.getElementById('video-setting-volume').value = this.settings.volume;
                document.getElementById('volume-display').innerText = this.settings.volume;
                document.getElementById('video-setting-mute').checked = this.settings.muteByDefault;
                document.getElementById('video-setting-loop').checked = this.settings.loopSingleVideo;
                document.getElementById('video-setting-controls').checked = this.settings.showControls;
                document.getElementById('video-setting-skip').checked = this.settings.showSkipButton;
                document.getElementById('video-setting-autoskip').value = this.settings.autoSkipAfter;
            },

            bindInputs() {
                document.getElementById('video-setting-volume').addEventListener('input', (e) => {
                    document.getElementById('volume-display').innerText = e.target.value;
                });
            },

            saveSettings() {
                this.settings = {
                    enabled: document.getElementById('video-setting-enabled').checked,
                    playbackMode: document.getElementById('video-setting-mode').value,
                    volume: parseInt(document.getElementById('video-setting-volume').value),
                    muteByDefault: document.getElementById('video-setting-mute').checked,
                    loopSingleVideo: document.getElementById('video-setting-loop').checked,
                    showControls: document.getElementById('video-setting-controls').checked,
                    showSkipButton: document.getElementById('video-setting-skip').checked,
                    autoSkipAfter: parseInt(document.getElementById('video-setting-autoskip').value)
                };
                localStorage.setItem('kiosk-video-settings', JSON.stringify(this.settings));
                kioskConfigManager.showToast('Video settings saved!');
            },

            setSubTab(tab) {
                ['videos', 'settings', 'analytics'].forEach(t => {
                    document.getElementById('subcontent-' + t).classList.add('hidden');
                    const btn = document.getElementById('subtab-' + t);
                    btn.classList.remove('border-amber-500', 'text-amber-600');
                    btn.classList.add('border-transparent', 'text-gray-600');
                });

                document.getElementById('subcontent-' + tab).classList.remove('hidden');
                const btn = document.getElementById('subtab-' + tab);
                btn.classList.remove('border-transparent', 'text-gray-600');
                btn.classList.add('border-amber-500', 'text-amber-600');

                if (tab === 'analytics') this.updateAnalytics();
            },

            updateVideoList() {
                const container = document.getElementById('subcontent-videos');
                document.getElementById('video-count').innerText = this.videos.length;

                if (this.videos.length === 0) {
                    container.innerHTML = `
                                <div class="col-span-full text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                    <i class="bi bi-camera-video text-5xl text-gray-400 mb-4 inline-block"></i>
                                    <p class="text-gray-600 mb-2 font-medium">No videos added yet</p>
                                    <p class="text-sm text-gray-500">Add your first promotional video to get started</p>
                                </div>
                             `;
                    return;
                }

                container.innerHTML = this.videos.map(video => `
                            <div class="bg-white rounded-xl border-2 overflow-hidden transition-all ${video.isActive ? 'border-green-200' : 'border-gray-200'}">
                                <!-- Thumbnail -->
                                <div class="aspect-video bg-gray-100 relative group">
                                     ${this.getThumbnailHtml(video)}

                                    <div class="absolute top-2 right-2 px-2 py-1 rounded text-xs font-medium ${video.isActive ? 'bg-green-500 text-white' : 'bg-gray-500 text-white'}">
                                        ${video.isActive ? 'Active' : 'Inactive'}
                                    </div>
                                    <div class="absolute bottom-2 left-2 px-2 py-1 bg-black/60 backdrop-blur-sm rounded text-white text-xs">
                                        Priority: ${video.priority}
                                    </div>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <button onclick="videoAdManager.editVideo('${video.id}')" class="p-2 bg-white rounded-full hover:bg-gray-100">
                                            <i class="bi bi-pencil-fill text-gray-700"></i>
                                        </button>
                                        <button onclick="videoAdManager.deleteVideo('${video.id}')" class="p-2 bg-white rounded-full hover:bg-red-50">
                                            <i class="bi bi-trash-fill text-red-600"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="p-4">
                                    <h3 class="text-lg text-gray-900 mb-1 line-clamp-1 font-medium">${video.title}</h3>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2 min-h-[2.5rem]">${video.desc || 'No description'}</p>

                                    <!-- Stats -->
                                    <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                                        <div class="flex items-center gap-1">
                                            <i class="bi bi-play-fill text-gray-400"></i>
                                            ${video.stats?.plays || 0} plays
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <i class="bi bi-clock text-gray-400"></i>
                                            ${video.duration}s
                                        </div>
                                    </div>

                                    <!-- Toggle Button -->
                                    <button onclick="videoAdManager.toggleActive('${video.id}')"
                                        class="w-full px-3 py-2 rounded-lg text-sm transition-colors font-medium ${video.isActive ? 'bg-gray-100 hover:bg-gray-200 text-gray-700' : 'bg-green-100 hover:bg-green-200 text-green-700'}">
                                        ${video.isActive ? '<i class="bi bi-eye-slash-fill mr-1"></i>Deactivate' : '<i class="bi bi-eye-fill mr-1"></i>Activate'}
                                    </button>
                                </div>
                            </div>
                        `).join('');
            },

            getThumbnailHtml(video) {
                // Mock thumbnail logic
                let icon = 'bi-camera-video';
                let color = 'text-gray-400';
                if (video.type === 'youtube') { icon = 'bi-youtube'; color = 'text-red-500'; }
                if (video.type === 'image') { icon = 'bi-image'; color = 'text-blue-500'; }

                return `
                            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                <i class="bi ${icon} text-5xl ${color}"></i>
                            </div>
                         `;
            },

            openModal(videoId = null) {
                document.getElementById('video-modal').classList.remove('hidden');
                if (videoId) {
                    const video = this.videos.find(v => v.id === videoId);
                    document.getElementById('modal-title').innerText = 'Edit Video';
                    document.getElementById('video-id').value = video.id;
                    document.getElementById('video-title').value = video.title;
                    document.getElementById('video-desc').value = video.desc;
                    document.getElementById('video-type').value = video.type;
                    document.getElementById('video-url').value = video.url;
                    document.getElementById('video-duration').value = video.duration;
                    document.getElementById('video-priority').value = video.priority;
                    document.getElementById('video-active').checked = video.isActive;
                } else {
                    document.getElementById('modal-title').innerText = 'Add New Video';
                    document.getElementById('video-id').value = '';
                    document.getElementById('video-title').value = '';
                    document.getElementById('video-desc').value = '';
                    document.getElementById('video-type').value = 'video';
                    document.getElementById('video-url').value = '';
                    document.getElementById('video-duration').value = 30;
                    document.getElementById('video-priority').value = 5;
                    document.getElementById('video-active').checked = true;
                }
            },

            closeModal() {
                document.getElementById('video-modal').classList.add('hidden');
            },

            submitModal() {
                const id = document.getElementById('video-id').value;
                const title = document.getElementById('video-title').value;
                const url = document.getElementById('video-url').value;

                if (!title || !url) {
                    kioskConfigManager.showToast('Please fill required fields', 'info');
                    return;
                }

                const videoData = {
                    id: id || 'vid_' + Date.now(),
                    title,
                    desc: document.getElementById('video-desc').value,
                    type: document.getElementById('video-type').value,
                    url,
                    duration: parseInt(document.getElementById('video-duration').value),
                    priority: parseInt(document.getElementById('video-priority').value),
                    isActive: document.getElementById('video-active').checked,
                    stats: id ? (this.videos.find(v => v.id === id)?.stats || { plays: 0, skips: 0 }) : { plays: 0, skips: 0 }
                };

                if (id) {
                    const idx = this.videos.findIndex(v => v.id === id);
                    this.videos[idx] = videoData;
                } else {
                    this.videos.push(videoData);
                }

                this.saveVideos();
                this.updateVideoList();
                this.closeModal();
                kioskConfigManager.showToast(id ? 'Video updated' : 'Video added');
            },

            deleteVideo(id) {
                if (confirm('Delete this video?')) {
                    this.videos = this.videos.filter(v => v.id !== id);
                    this.saveVideos();
                    this.updateVideoList();
                    kioskConfigManager.showToast('Video deleted');
                }
            },

            toggleActive(id) {
                const video = this.videos.find(v => v.id === id);
                if (video) {
                    video.isActive = !video.isActive;
                    this.saveVideos();
                    this.updateVideoList();
                }
            },

            saveVideos() {
                localStorage.setItem('kiosk-video-ads', JSON.stringify(this.videos));
            },

            editVideo(id) {
                this.openModal(id);
            },

            updateAnalytics() {
                const totalVideos = this.videos.length;
                const activeVideos = this.videos.filter(v => v.isActive).length;
                const totalPlays = this.videos.reduce((sum, v) => sum + (v.stats?.plays || 0), 0);
                const totalSkips = this.videos.reduce((sum, v) => sum + (v.stats?.skips || 0), 0);
                const completionRate = totalPlays > 0 ? ((totalPlays - totalSkips) / totalPlays * 100).toFixed(1) : 0;

                document.getElementById('stat-total-videos').innerText = totalVideos;
                document.getElementById('stat-active-videos').innerText = activeVideos;
                document.getElementById('stat-total-plays').innerText = totalPlays;
                document.getElementById('stat-completion').innerText = completionRate + '%';

                // Top Videos
                const topVideos = [...this.videos].sort((a, b) => (b.stats?.plays || 0) - (a.stats?.plays || 0)).slice(0, 5);
                const list = document.getElementById('top-videos-list');

                if (topVideos.length === 0) {
                    list.innerHTML = '<p class="text-gray-500 text-sm">No data available</p>';
                    return;
                }

                list.innerHTML = topVideos.map((v, i) => `
                            <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold text-sm">
                                    ${i + 1}
                                </div>
                                <div class="flex-1">
                                    <p class="text-gray-900 font-medium text-sm line-clamp-1">${v.title}</p>
                                    <p class="text-xs text-gray-600">${v.stats?.plays || 0} plays • ${v.stats?.skips || 0} skips</p>
                                </div>
                                <div class="text-right">
                                     <p class="text-sm text-gray-600 font-medium">
                                        ${((v.stats?.plays || 0) > 0 ? (((v.stats?.plays - v.stats?.skips) / v.stats?.plays) * 100) : 0).toFixed(0)}%
                                     </p>
                                </div>
                            </div>
                        `).join('');
            }
        };

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            kioskConfigManager.init();
        });
    </script>
@endsection