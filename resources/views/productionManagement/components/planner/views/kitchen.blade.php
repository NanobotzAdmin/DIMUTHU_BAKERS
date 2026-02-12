<div id="kds-root" class="h-screen flex flex-col bg-gray-50 overflow-hidden font-sans">

    <div class="bg-white border-b border-gray-200 px-6 py-4 flex-none" id="kds-header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="monitor" class="w-6 h-6 text-[#D4A017]"></i>
                        <h2 class="text-2xl font-semibold text-gray-900" id="header-title">Kitchen Display</h2>
                    </div>
                    <p class="text-sm text-gray-500">Real-time production monitoring</p>
                </div>

                <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100" id="clock-container">
                    <i data-lucide="clock" class="w-5 h-5 text-gray-600"></i>
                    <div>
                        <div class="text-2xl font-bold text-gray-900" id="clock-time">--:--</div>
                        <div class="text-xs text-gray-500" id="clock-date">---</div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4" id="stats-bar">
            </div>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 px-6 py-3 flex-none" id="kds-controls">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <select id="filter-dept" onchange="KDSystem.handleFilter()"
                    class="h-9 w-[200px] rounded-md border border-gray-300 px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-[#D4A017]">
                    <option value="all">All Departments</option>
                    <option value="dept-bakery">Main Bakery</option>
                    <option value="dept-pastry">Pastry Department</option>
                    <option value="dept-decoration">Cake Decoration</option>
                    <option value="dept-prep">Prep & Assembly</option>
                </select>

                <select id="filter-status" onchange="KDSystem.handleFilter()"
                    class="h-9 w-[140px] rounded-md border border-gray-300 px-3 py-1 text-sm shadow-sm focus:outline-none focus:ring-1 focus:ring-[#D4A017]">
                    <option value="active">Active Only</option>
                    <option value="all">All Tasks</option>
                </select>

                <div class="flex items-center gap-1 border border-gray-200 rounded-lg p-1 bg-white">
                    <button onclick="KDSystem.setLayout('grid')" id="btn-grid"
                        class="p-1 rounded hover:bg-gray-100 bg-gray-100">
                        <i data-lucide="grid-3x3" class="w-4 h-4 text-gray-700"></i>
                    </button>
                    <button onclick="KDSystem.setLayout('list')" id="btn-list" class="p-1 rounded hover:bg-gray-100">
                        <i data-lucide="list" class="w-4 h-4 text-gray-700"></i>
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="KDSystem.toggleAutoRefresh()" id="btn-refresh"
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium h-9 px-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-900 shadow-sm transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2 animate-spin"></i>
                    Auto-Refresh
                </button>
                <button onclick="KDSystem.toggleFullscreen()"
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium h-9 px-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-900 shadow-sm transition-colors">
                    <i data-lucide="maximize" class="w-4 h-4 mr-2"></i>
                    Fullscreen
                </button>
            </div>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-6" id="task-container">
    </div>

    <div id="qc-modal" class="fixed inset-0 z-50 bg-black/80 hidden items-center justify-center p-4">
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 mb-1">
                    <i data-lucide="clipboard-check" class="w-5 h-5 text-[#D4A017]"></i>
                    <h3 class="text-lg font-semibold">Pre-Production Quality Checklist</h3>
                </div>
                <p class="text-sm text-gray-500" id="qc-subtitle"></p>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6" id="qc-content">
                <!-- Injected Checks -->
            </div>

            <!-- Progress Bar Section -->
            <div class="px-6 pb-2">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between text-sm mb-2">
                        <span>Required Checks Completed:</span>
                        <span class="font-semibold" id="qc-progress-text">0 / 0</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div id="qc-progress-bar" class="bg-green-600 h-2 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 flex justify-end gap-2">
                <button onclick="KDSystem.closeModal('qc-modal')"
                    class="px-4 py-2 border border-gray-300 bg-white hover:bg-gray-50 rounded-md text-sm font-medium">Cancel</button>
                <button id="btn-start-production" onclick="KDSystem.confirmQCStart()" disabled
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-medium flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="play" class="w-4 h-4"></i>
                    Start Production
                </button>
            </div>
        </div>
    </div>

    <div id="stock-modal" class="fixed inset-0 z-50 bg-black/80 hidden items-center justify-center p-4">
        <div
            class="bg-white rounded-lg shadow-lg w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <i data-lucide="package" class="w-5 h-5 text-[#D4A017]"></i>
                    <h3 class="text-lg font-semibold">Ingredient Stock Status</h3>
                </div>
                <p class="text-sm text-gray-500" id="stock-subtitle">Checking inventory...</p>
            </div>

            <div class="p-6 space-y-3" id="stock-content">
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end">
                <button onclick="KDSystem.closeModal('stock-modal')"
                    class="px-4 py-2 bg-gray-900 text-white hover:bg-gray-800 rounded-md text-sm font-medium">Close</button>
            </div>
        </div>
    </div>

</div>

<script>
    const KDSystem = (function () {
        // --- State ---
        const state = {
            tasks: [], // Populated in init
            currentTime: new Date(),
            layout: 'grid',
            filterDept: 'all',
            filterStatus: 'active',
            autoRefresh: true,
            fullScreen: false,
            activeQCTaskId: null
        };

        // --- Mock Data ---
        const MOCK_TASKS = [
            { id: 't1', order: 'ORD-001', recipe: 'Sourdough Bread', qty: 20, unit: 'loaves', start: '05:00', duration: 180, status: 'in-progress', priority: 'high', resource: 'Deck Oven 1', staff: ['John'], dept: 'dept-bakery', estEnd: new Date(new Date().getTime() + 60 * 60 * 1000) },
            { id: 't2', order: 'ORD-002', recipe: 'Choco Croissants', qty: 30, unit: 'pcs', start: '06:00', duration: 120, status: 'in-progress', priority: 'urgent', resource: 'Pastry Oven A', staff: ['Anna'], dept: 'dept-pastry', notes: 'Pickup 10 AM', estEnd: new Date(new Date().getTime() + 30 * 60 * 1000) },
            { id: 't3', order: 'ORD-003', recipe: 'Wedding Cake', qty: 1, unit: 'cake', start: '07:00', duration: 240, status: 'scheduled', priority: 'urgent', resource: 'Deco Station 1', staff: ['Sarah'], dept: 'dept-decoration', notes: 'Handle with care' },
            { id: 't4', recipe: 'Baguettes', qty: 50, unit: 'loaves', start: '08:00', duration: 90, status: 'scheduled', priority: 'medium', resource: 'Deck Oven 2', staff: ['John'], dept: 'dept-bakery' },
            { id: 't5', recipe: 'Blueberry Muffin Mix', qty: 100, unit: 'units', start: '06:30', duration: 45, status: 'completed', priority: 'medium', resource: 'Prep Table 1', staff: ['Tom'], dept: 'dept-prep' },
            { id: 't6', recipe: 'Whole Wheat Bread', qty: 30, unit: 'loaves', start: '05:30', duration: 150, status: 'delayed', priority: 'high', resource: 'Deck Oven 2', staff: ['Maria'], dept: 'dept-bakery', notes: 'Mixer breakdown' }
        ];

        const MOCK_INVENTORY = {
            'Sourdough Bread': [
                { name: 'Flour', req: 10, avail: 50, unit: 'kg', status: 'in-stock' },
                { name: 'Water', req: 6, avail: 100, unit: 'L', status: 'in-stock' },
                { name: 'Starter', req: 2, avail: 1.5, unit: 'kg', status: 'low-stock' }
            ],
            'Choco Croissants': [
                { name: 'Butter', req: 5, avail: 2, unit: 'kg', status: 'out-of-stock' },
                { name: 'Chocolate', req: 2, avail: 10, unit: 'kg', status: 'in-stock' }
            ]
        };

        const MOCK_RECIPES = {
            'Sourdough Bread': {
                name: 'Sourdough Bread',
                description: 'Traditional slow-fermented sourdough bread with a crispy crust and chewy interior.',
                category: 'Bread',
                difficulty: 'medium',
                baseYield: 10,
                yieldUnit: 'loaves',
                productionTime: 180,
                restTime: 720,
                shelfLife: 48,
                ingredients: [
                    { id: 1, name: 'Bread Flour', quantity: 5, unit: 'kg' },
                    { id: 2, name: 'Water', quantity: 3.5, unit: 'L' },
                    { id: 3, name: 'Sourdough Starter', quantity: 1, unit: 'kg' },
                    { id: 4, name: 'Salt', quantity: 100, unit: 'g' }
                ],
                equipment: [
                    { id: 1, name: 'Deck Oven', type: 'oven', required: true },
                    { id: 2, name: 'Spiral Mixer', type: 'mixer', required: true },
                    { id: 3, name: 'Proofing Baskets', type: 'other', required: false }
                ],
                steps: [
                    { id: 1, stepNumber: 1, instruction: 'Mix flour and water (autolyse)', duration: 30 },
                    { id: 2, stepNumber: 2, instruction: 'Add starter and salt, mix to developed gluten', duration: 15, equipment: 'Spiral Mixer' },
                    { id: 3, stepNumber: 3, instruction: 'Bulk fermentation with folds', duration: 240 },
                    { id: 4, stepNumber: 4, instruction: 'Divide and shape', duration: 45 },
                    { id: 5, stepNumber: 5, instruction: 'Cold proof overnight', duration: 720, temperature: 4 },
                    { id: 6, stepNumber: 6, instruction: 'Bake with steam', duration: 45, temperature: 240, equipment: 'Deck Oven' }
                ],
                allergens: ['Gluten', 'Wheat'],
                tags: ['Vegan', 'Artisan']
            },
            'Choco Croissants': {
                name: 'Chocolate Croissants',
                description: 'Buttery, flaky croissants filled with dark chocolate batons.',
                category: 'Pastry',
                difficulty: 'hard',
                baseYield: 24,
                yieldUnit: 'pcs',
                productionTime: 240,
                restTime: 60,
                shelfLife: 24,
                ingredients: [
                    { id: 1, name: 'Pastry Flour', quantity: 2, unit: 'kg' },
                    { id: 2, name: 'Butter (Lamination)', quantity: 1, unit: 'kg', notes: 'Cold sheets' },
                    { id: 3, name: 'Dark Chocolate', quantity: 500, unit: 'g' }
                ],
                equipment: [
                    { id: 1, name: 'Sheeter', type: 'other', required: true },
                    { id: 2, name: 'Convection Oven', type: 'oven', required: true }
                ],
                steps: [
                    { id: 1, stepNumber: 1, instruction: 'Prepare detrempe (dough)', duration: 30 },
                    { id: 2, stepNumber: 2, instruction: 'Lock in butter and perform folds', duration: 60 },
                    { id: 3, stepNumber: 3, instruction: 'Sheet and shape', duration: 45 }
                ],
                allergens: ['Dairy', 'Gluten', 'Soy'],
                tags: ['Breakfast', 'Premium']
            }
        };

        // --- Core Functions ---

        function init() {
            state.tasks = [...MOCK_TASKS];
            startClock();
            render();
            // Setup icons
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function startClock() {
            setInterval(() => {
                state.currentTime = new Date();
                updateClockDOM();
                updateTimesDOM(); // Update "X min left" without full re-render
            }, 1000);
            updateClockDOM();
        }

        function updateClockDOM() {
            const timeStr = state.currentTime.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const dateStr = state.currentTime.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' });
            document.getElementById('clock-time').innerText = timeStr;
            document.getElementById('clock-date').innerText = dateStr;
        }

        // --- Rendering ---

        function render() {
            renderStats();
            renderTasks();
            lucide.createIcons();
        }

        function renderStats() {
            const inProg = state.tasks.filter(t => t.status === 'in-progress').length;
            const sched = state.tasks.filter(t => t.status === 'scheduled').length;
            const delayed = state.tasks.filter(t => t.status === 'delayed').length;
            const urgent = state.tasks.filter(t => t.priority === 'urgent').length;

            const html = `
            <div class="text-center px-4 py-2 rounded-lg bg-green-50">
                <div class="text-2xl font-bold text-green-600">${inProg}</div>
                <div class="text-xs text-green-600">In Progress</div>
            </div>
            <div class="text-center px-4 py-2 rounded-lg bg-blue-50">
                <div class="text-2xl font-bold text-blue-600">${sched}</div>
                <div class="text-xs text-blue-600">Scheduled</div>
            </div>
            ${delayed > 0 ? `
            <div class="text-center px-4 py-2 rounded-lg bg-red-50">
                <div class="text-2xl font-bold text-red-600">${delayed}</div>
                <div class="text-xs text-red-600">Delayed</div>
            </div>` : ''}
            ${urgent > 0 ? `
            <div class="text-center px-4 py-2 rounded-lg bg-orange-50 animate-pulse">
                <div class="text-2xl font-bold text-orange-600">${urgent}</div>
                <div class="text-xs text-orange-600">Urgent</div>
            </div>` : ''}
        `;
            document.getElementById('stats-bar').innerHTML = html;
        }

        function renderTasks() {
            const container = document.getElementById('task-container');

            // Filter & Sort
            let filtered = state.tasks.filter(t => {
                const deptMatch = state.filterDept === 'all' || t.dept === state.filterDept;
                const statusMatch = state.filterStatus === 'all' ||
                    (state.filterStatus === 'active' && ['scheduled', 'in-progress', 'delayed'].includes(t.status));
                return deptMatch && statusMatch;
            });

            // Sort: Urgent > High > Medium > Low, then by Start Time
            const priorityOrder = { urgent: 0, high: 1, medium: 2, low: 3 };
            filtered.sort((a, b) => {
                if (a.priority !== b.priority) return priorityOrder[a.priority] - priorityOrder[b.priority];
                return a.start.localeCompare(b.start);
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <i data-lucide="check-circle" class="w-16 h-16 mb-4"></i>
                    <p class="text-lg">No active tasks</p>
                    <p class="text-sm">All caught up! 🎉</p>
                </div>`;
                return;
            }

            // Layout Config
            const isGrid = state.layout === 'grid';
            container.className = isGrid
                ? "p-6 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-4 overflow-y-auto"
                : "p-6 space-y-3 overflow-y-auto";

            container.innerHTML = filtered.map(task => renderTaskCard(task)).join('');
        }

        function renderTaskCard(t) {
            const isUrgent = t.priority === 'urgent';
            const isDelayed = t.status === 'delayed';

            // Dynamic Classes
            let borderClass = "border-l-4 border-l-transparent";
            if (isUrgent) borderClass = "border-l-4 border-l-orange-500";
            if (isDelayed) borderClass = "border-l-4 border-l-red-600";
            if (t.status === 'completed') borderClass = "border-l-4 border-l-gray-300 opacity-75";

            // Badges
            let statusBadge = '';
            if (t.status === 'in-progress') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"><i data-lucide="play" class="w-3 h-3 mr-1"></i> In Progress</span>`;
            if (t.status === 'scheduled') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"><i data-lucide="clock" class="w-3 h-3 mr-1"></i> Scheduled</span>`;
            if (t.status === 'delayed') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800"><i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i> Delayed</span>`;
            if (t.status === 'completed') statusBadge = `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800"><i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Completed</span>`;

            // Priority Dot
            const prioColors = { urgent: 'bg-red-600', high: 'bg-orange-500', medium: 'bg-blue-500', low: 'bg-green-500' };

            // Stock Logic (Mock)
            const inventory = MOCK_INVENTORY[t.recipe] || [];
            const isLow = inventory.some(i => i.status === 'low-stock');
            const isOut = inventory.some(i => i.status === 'out-of-stock');
            let stockBadge = `<span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200"><i data-lucide="check-circle" class="w-3 h-3 mr-1"></i> Available</span>`;
            if (isOut) stockBadge = `<span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200"><i data-lucide="x-circle" class="w-3 h-3 mr-1"></i> Out of Stock</span>`;
            else if (isLow) stockBadge = `<span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200"><i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i> Low Stock</span>`;

            // Time Left
            let timeLeftHtml = '';
            if (t.status === 'in-progress' && t.estEnd) {
                const diff = Math.floor((t.estEnd - new Date()) / 60000);
                const color = diff < 0 ? 'text-red-500' : (diff < 10 ? 'text-orange-500' : 'text-green-600');
                timeLeftHtml = `<span class="ml-auto font-medium ${color} text-sm">${diff}m left</span>`;
            }

            // Action Buttons
            let actionsHtml = '';
            if (t.status === 'scheduled') {
                actionsHtml = `
                <button onclick="KDSystem.openQCModal('${t.id}')" class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded text-sm font-medium flex items-center justify-center gap-1">
                    <i data-lucide="clipboard-check" class="w-4 h-4"></i> QC Check
                </button>
                <button onclick="KDSystem.openQCModal('${t.id}')" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm font-medium flex items-center justify-center gap-1">
                    <i data-lucide="play" class="w-4 h-4"></i> Start
                </button>
            `;
            } else if (t.status === 'in-progress') {
                actionsHtml = `
                <button onclick="KDSystem.completeTask('${t.id}')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-sm font-medium flex items-center justify-center gap-1">
                    <i data-lucide="check-circle" class="w-4 h-4"></i> Complete
                </button>
                <button onclick="KDSystem.delayTask('${t.id}')" class="bg-white border border-red-200 text-red-600 hover:bg-red-50 px-3 py-1.5 rounded text-sm font-medium">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                </button>
            `;
            } else if (t.status === 'delayed') {
                actionsHtml = `
                <button onclick="KDSystem.startTask('${t.id}')" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm font-medium flex items-center justify-center gap-1">
                    <i data-lucide="play" class="w-4 h-4"></i> Resume
                </button>
            `;
            }

            return `
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow ${borderClass}">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-semibold text-gray-900">${t.recipe}</h3>
                        <div class="w-2 h-2 rounded-full ${prioColors[t.priority]}"></div>
                    </div>
                    ${t.order ? `<p class="text-xs text-gray-500 mb-1">Order: ${t.order}</p>` : ''}
                    <div class="flex items-center gap-2 flex-wrap">
                        ${statusBadge}
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border border-gray-200 text-gray-600">${t.qty} ${t.unit}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2 mb-3 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                    <span>Start: ${t.start}</span>
                    ${timeLeftHtml}
                </div>
                <div class="flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                    <span>${t.resource}</span>
                </div>
                ${t.staff ? `
                <div class="flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4 text-gray-400"></i>
                    <span class="truncate">${t.staff.join(', ')}</span>
                </div>` : ''}

                <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 mt-1" onclick="KDSystem.openStockModal('${t.recipe}')">
                    <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                    <span>Ingredients:</span>
                    ${stockBadge}
                </div>

                ${t.notes ? `
                <div class="mt-2 p-2 rounded bg-yellow-50 border border-yellow-200 flex items-start gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-yellow-600 mt-0.5 flex-shrink-0"></i>
                    <p class="text-xs text-yellow-800">${t.notes}</p>
                </div>` : ''}
            </div>

            <div class="flex items-center gap-2 mt-auto">
                <button onclick="KDSystem.openRecipeModal('${t.recipe}')" class="flex-1 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-1.5 rounded text-sm font-medium flex items-center justify-center gap-1">
                    <i data-lucide="book-open" class="w-4 h-4"></i> Recipe
                </button>
            </div>
            ${actionsHtml ? `<div class="flex items-center gap-2 mt-2">${actionsHtml}</div>` : ''}
        </div>
        `;
        }

        function updateTimesDOM() {
            // Optimization: Only update specific text nodes if performance issues arise
            // For now, re-render tasks occasionally or manipulate DOM directly
            // Currently handled by full render on state change or basic interval logic could go here
        }

        // --- Actions ---

        function startTask(id) {
            const idx = state.tasks.findIndex(t => t.id === id);
            if (idx > -1) {
                state.tasks[idx].status = 'in-progress';
                state.tasks[idx].estEnd = new Date(new Date().getTime() + state.tasks[idx].duration * 60000);
                render();
                // playSound();
            }
        }

        function delayTask(id) {
            const idx = state.tasks.findIndex(t => t.id === id);
            if (idx > -1) {
                state.tasks[idx].status = 'delayed';
                render();
            }
        }

        function completeTask(id) {
            const idx = state.tasks.findIndex(t => t.id === id);
            if (idx > -1) {
                const task = state.tasks[idx];
                CompletionModal.open(task, (data) => {
                    console.log("Production Completed:", data);
                    state.tasks[idx].status = 'completed';
                    state.tasks[idx].actualYield = data.actualYield;
                    state.tasks[idx].completionData = data;
                    render();
                    // Optional: Send data to backend here
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Completed!',
                        text: `Yield: ${data.actualYield} ${task.unit}. Batch: ${data.batchNumber}`,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                });
            }
        }

        // --- Modals ---

        function openRecipeModal(recipeName) {
            const recipe = MOCK_RECIPES[recipeName];
            if (recipe) {
                RecipeModal.open(recipe, (r) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Recipe Added!',
                        text: 'Recipe added to timeline (Mock)',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Recipe Not Found!',
                    text: 'Recipe details not found for: ' + recipeName,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        }

        function openQCModal(taskId) {
            state.activeQCTaskId = taskId;
            const task = state.tasks.find(t => t.id === taskId);
            if (!task) return;

            document.getElementById('qc-subtitle').innerHTML = `Complete all required checks before starting production: <strong>${task.recipe}</strong>`;

            // Define Checks with Categories
            const allChecks = [
                { id: 'qc1', label: 'Equipment Clean & Operational', category: 'equipment', required: true },
                { id: 'qc2', label: 'All Tools Present', category: 'equipment', required: false },
                { id: 'qc3', label: 'Ingredients Measured & Prepped', category: 'ingredients', required: true },
                { id: 'qc4', label: 'Ingredient Freshness Verified', category: 'ingredients', required: true },
                { id: 'qc5', label: 'Hygiene & PPE Verified', category: 'safety', required: true },
                { id: 'qc6', label: 'Workstation Sanitized', category: 'safety', required: false },
                { id: 'qc7', label: 'Oven Pre-heated', category: 'preparation', required: false }
            ];

            // Group by Category
            const categories = ['equipment', 'ingredients', 'safety', 'preparation'];
            const catIcons = { equipment: 'package', ingredients: 'chef-hat', safety: 'alert-circle', preparation: 'check-circle' };

            let html = '';
            categories.forEach(cat => {
                const checks = allChecks.filter(c => c.category === cat);
                if (checks.length === 0) return;

                html += `
            <div class="border rounded-lg p-4">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="${catIcons[cat] || 'circle'}" class="w-5 h-5 text-[#D4A017]"></i>
                    <h4 class="font-semibold capitalize text-gray-900">${cat} Checks</h4>
                </div>
                <div class="space-y-2">
                    ${checks.map(check => `
                    <div class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded">
                        <input type="checkbox" id="${check.id}" data-required="${check.required}" onchange="KDSystem.checkQCRequirement()" class="mt-1 h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-600 cursor-pointer">
                        <label for="${check.id}" class="flex-1 text-sm cursor-pointer text-gray-700">
                            ${check.label}
                            ${check.required ? '<span class="text-red-500 ml-1">*</span>' : ''}
                        </label>
                    </div>
                    `).join('')}
                </div>
            </div>`;
            });

            document.getElementById('qc-content').innerHTML = html;

            document.getElementById('qc-modal').classList.remove('hidden');
            document.getElementById('qc-modal').classList.add('flex');

            // Initial Progress Check
            checkQCRequirement();
            lucide.createIcons();
        }

        function checkQCRequirement() {
            const requiredCheckboxes = document.querySelectorAll('#qc-content input[data-required="true"]');
            const totalRequired = requiredCheckboxes.length;

            let completed = 0;
            requiredCheckboxes.forEach(cb => {
                if (cb.checked) completed++;
            });

            // Update Progress Bar
            const percent = totalRequired > 0 ? (completed / totalRequired) * 100 : 100;
            document.getElementById('qc-progress-bar').style.width = `${percent}%`;
            document.getElementById('qc-progress-text').innerText = `${completed} / ${totalRequired}`;

            // Enable/Disable Button
            const btn = document.getElementById('btn-start-production');
            if (completed === totalRequired) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        function confirmQCStart() {
            if (state.activeQCTaskId) {
                startTask(state.activeQCTaskId);
                closeModal('qc-modal');
            }
        }

        function openStockModal(recipeName) {
            document.getElementById('stock-subtitle').innerText = `Inventory for: ${recipeName}`;
            const items = MOCK_INVENTORY[recipeName] || []; // Fallback empty

            let html = '';
            if (items.length === 0) {
                html = '<p class="text-gray-500 italic">No inventory data available for this recipe.</p>';
            } else {
                html = items.map(i => {
                    let colorClass = 'bg-green-50 border-green-200';
                    let icon = 'check-circle';
                    let text = 'text-green-700';

                    if (i.status === 'out-of-stock') { colorClass = 'bg-red-50 border-red-200'; icon = 'x-circle'; text = 'text-red-700'; }
                    if (i.status === 'low-stock') { colorClass = 'bg-yellow-50 border-yellow-200'; icon = 'alert-triangle'; text = 'text-yellow-700'; }

                    return `
                <div class="p-4 rounded-lg border ${colorClass}">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-semibold text-gray-900">${i.name}</h4>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border border-transparent ${text.replace('text-', 'bg-').replace('700', '100')} ${text}">
                            <i data-lucide="${icon}" class="w-3 h-3 mr-1"></i> ${i.status}
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-sm">
                        <div><p class="text-gray-500">Required</p><p class="font-semibold">${i.req} ${i.unit}</p></div>
                        <div><p class="text-gray-500">Available</p><p class="font-semibold">${i.avail} ${i.unit}</p></div>
                        <div><p class="text-gray-500">Status</p><p class="${text}">${i.status === 'in-stock' ? 'OK' : 'Issue'}</p></div>
                    </div>
                </div>`;
                }).join('');
            }

            document.getElementById('stock-content').innerHTML = html;
            document.getElementById('stock-modal').classList.remove('hidden');
            document.getElementById('stock-modal').classList.add('flex');
            lucide.createIcons();
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.getElementById(id).classList.remove('flex');
        }

        // --- UI Handlers ---

        function handleFilter() {
            state.filterDept = document.getElementById('filter-dept').value;
            state.filterStatus = document.getElementById('filter-status').value;
            render();
        }

        function setLayout(mode) {
            state.layout = mode;
            document.getElementById('btn-grid').className = mode === 'grid' ? "p-1 rounded bg-gray-200" : "p-1 rounded hover:bg-gray-100";
            document.getElementById('btn-list').className = mode === 'list' ? "p-1 rounded bg-gray-200" : "p-1 rounded hover:bg-gray-100";
            render();
        }

        function toggleAutoRefresh() {
            state.autoRefresh = !state.autoRefresh;
            const icon = document.querySelector('#btn-refresh i');
            if (state.autoRefresh) icon.classList.add('animate-spin');
            else icon.classList.remove('animate-spin');
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }

        // Expose public methods
        return {
            init,
            handleFilter,
            setLayout,
            toggleAutoRefresh,
            toggleFullscreen,
            startTask,
            delayTask,
            completeTask,
            openQCModal,
            openRecipeModal,
            closeModal,
            checkQCRequirement,
            confirmQCStart,
            openStockModal
        };
    })();

    // Initialize on Load
    document.addEventListener("DOMContentLoaded", KDSystem.init);
</script>

@include('productionManagement.components.planner.modals.recipeDetail')
@include('productionManagement.components.planner.modals.productionCompletion')