@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 p-6" id="overhead-dashboard">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Overhead Management System</h1>
                <p class="text-gray-600 mt-1">Comprehensive Activity-Based Costing & Budget Control</p>
            </div>

            <!-- View Mode Toggle -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2 flex items-center gap-3">
                <i class="bi bi-gear text-gray-400 text-lg ml-2"></i>
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700 cursor-pointer select-none"
                        id="view-mode-label">Advanced Mode</label>
                    <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                        <input type="checkbox" name="toggle" id="view-mode-toggle"
                            class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 left-0 transition-all duration-300"
                            onchange="dashboardManager.toggleViewMode()" />
                        <label for="view-mode-toggle"
                            class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer transition-colors duration-300"></label>
                    </div>
                </div>
                <span id="mode-badge"
                    class="px-2 py-0.5 rounded text-xs border bg-purple-50 text-purple-700 border-purple-200 flex items-center gap-1">
                    <i class="bi bi-stars"></i> All Features
                </span>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <i class="bi bi-currency-dollar text-xl text-amber-600"></i>
                    </div>
                    <div class="text-sm text-gray-600 font-medium">Annual Overhead</div>
                </div>
                <div class="text-2xl font-bold text-gray-900">Rs {{ number_format($dashboardStats['totalOverhead']) }}</div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <i class="bi bi-box-seam text-xl text-purple-600"></i>
                    </div>
                    <div class="text-sm text-gray-600 font-medium">Cost Pools</div>
                </div>
                <div class="text-2xl font-bold text-purple-600">{{ $dashboardStats['costPools'] }}</div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <i class="bi bi-activity text-xl text-indigo-600"></i>
                    </div>
                    <div class="text-sm text-gray-600 font-medium">Activities</div>
                </div>
                <div class="text-2xl font-bold text-indigo-600">{{ $dashboardStats['activities'] }}</div>
            </div>

            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <i class="bi bi-bullseye text-xl text-blue-600"></i>
                    </div>
                    <div class="text-sm text-gray-600 font-medium">Allocations</div>
                </div>
                <div class="text-2xl font-bold text-blue-600">{{ $dashboardStats['allocations'] }}</div>
            </div>

            <!-- GL Integration Status Card -->
            <div class="col-span-1 md:col-span-4 lg:col-span-1 cursor-pointer transition-all hover:shadow-md"
                onclick="window.location.href='/overhead/gl-mapping'">
                @php
                    $gl = $dashboardStats['glIntegration'];
                    $glClass = $gl['isMappingComplete'] ? 'border-green-200 bg-green-50' : 'border-yellow-200 bg-yellow-50';
                    $iconBg = $gl['isMappingComplete'] ? 'bg-green-200' : 'bg-yellow-200';
                    $iconColor = $gl['isMappingComplete'] ? 'text-green-700' : 'text-yellow-700';
                    $textColor = $gl['isMappingComplete'] ? 'text-green-700' : 'text-yellow-700';
                @endphp
                <div class="rounded-xl p-5 border {{ $glClass }} h-full flex flex-col justify-center">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg {{ $iconBg }}">
                                <i class="bi bi-link-45deg text-xl {{ $iconColor }}"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium {{ $textColor }}">GL Integration</div>
                                <div class="text-xs text-gray-500">
                                    {{ $gl['isMappingComplete'] ? 'Complete' : 'Needs mapping' }}</div>
                            </div>
                        </div>
                        <div class="text-2xl font-bold {{ $textColor }}">
                            {{ $gl['mappedPools'] }}/{{ $gl['totalPools'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Mode Banner -->
        <div id="basic-mode-banner" class="hidden mb-8">
            <div
                class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-xl p-4 flex items-start gap-4">
                <div class="p-2 bg-blue-100 rounded-lg flex-shrink-0">
                    <i class="bi bi-stars text-xl text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-gray-900 mb-1">You're in Basic Mode</h3>
                    <p class="text-sm text-gray-600">
                        Showing essential features for small bakeries. Switch to Advanced Mode to access Activity-Based
                        Costing,
                        detailed cost reports, product costing analysis, and forecasting.
                    </p>
                </div>
                <button onclick="dashboardManager.setMode('advanced')"
                    class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 flex items-center gap-2">
                    <i class="bi bi-gem"></i> View All Features
                </button>
            </div>
        </div>

        <!-- Phases Grid -->
        <div class="space-y-6" id="phases-container">
            <!-- Dynamic Content -->
        </div>

        <!-- Quick Start Guide -->
        <div class="mt-8 bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6">
            <div class="flex items-center gap-2 mb-4">
                <i class="bi bi-graph-up-arrow text-amber-600 text-xl"></i>
                <h2 class="text-lg font-bold text-gray-900">Getting Started with Overhead Management</h2>
            </div>
            <div class="space-y-2 text-sm text-gray-700">
                <div class="flex gap-2">
                    <span class="font-bold text-amber-600">1.</span>
                    <span><strong>Phase 1:</strong> Set up cost pools to categorize your overhead expenses (utilities, rent,
                        etc.)</span>
                </div>
                <div class="flex gap-2 feature-advanced">
                    <span class="font-bold text-amber-600">2.</span>
                    <span><strong>Phase 2:</strong> Configure activities and cost drivers for activity-based costing <span
                            class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded ml-1">Professional</span></span>
                </div>
                <div class="flex gap-2">
                    <span class="font-bold text-amber-600 step-number-3">3.</span>
                    <span><strong>Phase 3:</strong> Use the Allocation Wizard to distribute overhead costs to
                        products</span>
                </div>
                <div class="flex gap-2">
                    <span class="font-bold text-amber-600 step-number-4">4.</span>
                    <span><strong>Phase 4:</strong> Analyze cost allocation with <span class="txt-advanced">comprehensive
                            reports and</span> analytics</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles for Toggle -->
    <style>
        .toggle-checkbox:checked {
            right: 0;
            border-color: #7c3aed;
            /* purple-600 */
        }

        .toggle-checkbox:checked+.toggle-label {
            background-color: #7c3aed;
            /* purple-600 */
        }

        .toggle-checkbox {
            right: 50%;
        }
    </style>

    <script>
        const dashboardManager = {
            state: {
                viewMode: localStorage.getItem('overhead-view-mode') || 'advanced'
            },

            phases: [
                {
                    phase: 1,
                    title: 'Cost Pools Management',
                    description: 'Define and manage overhead cost pools',
                    icon: 'bi-box-seam',
                    color: 'text-purple-600',
                    bgColor: 'bg-purple-100',
                    cssColor: 'purple-600',
                    modules: [
                        { name: 'Cost Pools', path: '/cost-pools', icon: 'bi-box-seam', description: 'Create and manage cost pools', tier: 'basic' }
                    ]
                },
                {
                    phase: 2,
                    title: 'GL Integration & Expense Recording',
                    description: 'Map overhead to GL accounts and record expenses',
                    icon: 'bi-link-45deg',
                    color: 'text-emerald-600',
                    bgColor: 'bg-emerald-100',
                    cssColor: 'emerald-600',
                    modules: [
                        { name: 'GL Account Mapping', path: '/gl-account-mapping', icon: 'bi-link-45deg', description: 'Map overhead categories to GL accounts', tier: 'basic' },
                        { name: 'Expense Recording', path: '/expense-recording', icon: 'bi-currency-dollar', description: 'Record overhead expenses with auto-JE', tier: 'basic' }
                    ]
                },
                {
                    phase: 3,
                    title: 'Activity-Based Costing',
                    description: 'Set up activities and cost drivers',
                    icon: 'bi-activity',
                    color: 'text-indigo-600',
                    bgColor: 'bg-indigo-100',
                    cssColor: 'indigo-600',
                    modules: [
                        { name: 'ABC Setup', path: '/activity-based-costing', icon: 'bi-activity', description: 'Configure ABC system', isPremium: true, tier: 'professional' }
                    ]
                },
                {
                    phase: 4,
                    title: 'Allocation Wizard & Posting',
                    description: 'Allocate overhead and post to GL',
                    icon: 'bi-magic',
                    color: 'text-amber-600',
                    bgColor: 'bg-amber-100',
                    cssColor: 'amber-600',
                    modules: [
                        { name: 'Allocation Wizard', path: '/allocation-wizard', icon: 'bi-magic', description: 'Run allocation process', tier: 'basic' },
                        { name: 'Allocation Posting', path: '/allocation-posting', icon: 'bi-send', description: 'Post allocations with auto-JE', tier: 'basic' },
                        { name: 'Variance Reconciliation', path: '/variance-reconciliation', icon: 'bi-graph-up', description: 'Reconcile over/under applied OH', tier: 'basic' },
                        { name: 'Allocation History', path: '/allocation-history', icon: 'bi-calendar-event', description: 'View past allocations', tier: 'basic' }
                    ]
                },
                {
                    phase: 5,
                    title: 'Reporting & Analytics',
                    description: 'Analyze overhead costs and trends',
                    icon: 'bi-bar-chart-fill',
                    color: 'text-blue-600',
                    bgColor: 'bg-blue-100',
                    cssColor: 'blue-600',
                    modules: [
                        { name: 'Overhead Analytics', path: '/analytics-dashboard', icon: 'bi-speedometer2', description: 'Dashboard and KPIs', tier: 'basic' },
                        { name: 'Cost Allocation Report', path: '/cost-allocation-report', icon: 'bi-file-earmark-text', description: 'Detailed allocation reports', tier: 'professional' },
                        { name: 'Product Costing Analysis', path: '/product-costing-analysis', icon: 'bi-bullseye', description: 'Product profitability', tier: 'professional' }
                    ]
                },
                {
                    phase: 6,
                    title: 'Budget & Variance Control',
                    description: 'Plan budgets and analyze variances',
                    icon: 'bi-wallet2',
                    color: 'text-green-600',
                    bgColor: 'bg-green-100',
                    cssColor: 'green-600',
                    modules: [
                        { name: 'Budget Planning', path: '/budget-planning', icon: 'bi-calendar-check', description: 'Create and manage budgets', tier: 'basic' },
                        { name: 'Variance Analysis', path: '/variance-analysis', icon: 'bi-exclamation-triangle', description: 'Budget vs actual analysis', tier: 'basic' },
                        { name: 'Budget Forecasting', path: '/budget-forecasting', icon: 'bi-cpu', description: 'AI-powered forecasting', isPremium: true, tier: 'enterprise' }
                    ]
                }
            ],

            init() {
                this.updateUI();
            },

            toggleViewMode() {
                this.state.viewMode = this.state.viewMode === 'advanced' ? 'basic' : 'advanced';
                localStorage.setItem('overhead-view-mode', this.state.viewMode);
                this.updateUI();
            },

            setMode(mode) {
                this.state.viewMode = mode;
                localStorage.setItem('overhead-view-mode', mode);
                document.getElementById('view-mode-toggle').checked = (mode === 'advanced');
                this.updateUI();
            },

            updateUI() {
                const isAdvanced = this.state.viewMode === 'advanced';

                // Toggle Checkbox visual
                document.getElementById('view-mode-toggle').checked = isAdvanced;
                document.getElementById('view-mode-label').innerText = isAdvanced ? 'Advanced Mode' : 'Basic Mode';

                // Badge
                const badge = document.getElementById('mode-badge');
                if (isAdvanced) {
                    badge.innerHTML = '<i class="bi bi-stars"></i> All Features';
                    badge.className = "px-2 py-0.5 rounded text-xs border bg-purple-50 text-purple-700 border-purple-200 flex items-center gap-1";
                } else {
                    badge.innerHTML = 'Simplified View';
                    badge.className = "px-2 py-0.5 rounded text-xs border bg-gray-50 text-gray-600 border-gray-200 flex items-center gap-1";
                }

                // Banner
                const banner = document.getElementById('basic-mode-banner');
                if (!isAdvanced) {
                    banner.classList.remove('hidden');
                } else {
                    banner.classList.add('hidden');
                }

                // Render Phases
                const container = document.getElementById('phases-container');
                container.innerHTML = this.phases.map(p => this.renderPhase(p, isAdvanced)).join('');

                // Quick Guide numbering update (simple visual fix)
                if (isAdvanced) {
                    document.querySelectorAll('.feature-advanced').forEach(e => e.classList.remove('hidden'));
                    document.querySelector('.step-number-3').innerText = '3.';
                    document.querySelector('.step-number-4').innerText = '4.';
                    document.querySelectorAll('.txt-advanced').forEach(e => e.classList.remove('hidden'));
                } else {
                    document.querySelectorAll('.feature-advanced').forEach(e => e.classList.add('hidden'));
                    document.querySelector('.step-number-3').innerText = '2.';
                    document.querySelector('.step-number-4').innerText = '3.';
                    document.querySelectorAll('.txt-advanced').forEach(e => e.classList.add('hidden'));
                }
            },

            renderPhase(phase, isAdvanced) {
                const visibleModules = phase.modules.filter(m => isAdvanced || m.tier === 'basic');

                // If no modules visible in this phase for basic mode (e.g. Activity Based Costing), hide phase?
                // React code hides modules but keeps phase? Let's see. 
                // React code: "Filter phases based on view mode? No, getVisibleModules map inside phase".
                // Detailed Check: Phase 3 only has 'professional' modules. In Basic mode, visibleModules would be empty.
                // We should probably hide empty phases or show empty state? React renders Card but content empty?
                // Let's hide the phase if no modules visible to be cleaner.
                if (visibleModules.length === 0) return '';

                return `
                        <div class="bg-white rounded-xl shadow-sm border-l-4 overflow-hidden" style="border-left-color: var(--color-${phase.cssColor}); border-left-width: 4px; border: 1px solid #e5e7eb; border-left: 4px solid;">
                             <div class="p-6 border-b border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 ${phase.bgColor} rounded-lg">
                                        <i class="bi ${phase.icon} text-xl ${phase.color}"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">Phase ${phase.phase}</span>
                                            <h2 class="text-lg font-bold text-gray-900">${phase.title}</h2>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">${phase.description}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-6 bg-gray-50/50">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    ${visibleModules.map(m => this.renderModuleCard(m, phase)).join('')}
                                </div>
                            </div>
                        </div>
                     `;
            },

            renderModuleCard(module, phase) {
                let badge = '';
                if (module.tier === 'professional') badge = '<span class="ml-auto px-2 py-0.5 rounded text-[10px] bg-blue-500 text-white flex items-center gap-1"><i class="bi bi-gem"></i> Professional</span>';
                if (module.tier === 'enterprise') badge = '<span class="ml-auto px-2 py-0.5 rounded text-[10px] bg-purple-600 text-white flex items-center gap-1"><i class="bi bi-building"></i> Enterprise</span>';

                return `
                        <div onclick="window.location.href='${module.path}'" 
                            class="bg-white p-4 rounded-xl border-2 border-transparent hover:border-amber-400 hover:shadow-lg transition-all cursor-pointer group shadow-sm flex flex-col h-full relative">
                             <div class="flex items-start gap-3 mb-2">
                                 <div class="p-2 ${phase.bgColor} rounded-lg group-hover:scale-110 transition-transform">
                                     <i class="bi ${module.icon} text-lg ${phase.color}"></i>
                                 </div>
                                 <div class="flex-1 min-w-0">
                                     <div class="font-bold text-gray-900 truncate">${module.name}</div>
                                     <div class="text-xs text-gray-500 line-clamp-2 mt-0.5">${module.description}</div>
                                 </div>
                             </div>
                             <div class="mt-auto pt-2 flex items-center justify-between border-t border-gray-50">
                                 <i class="bi bi-arrow-right text-gray-300 group-hover:text-amber-500 transition-colors"></i>
                                 ${badge}
                             </div>
                        </div>
                    `;
            }
        };

        // Inject dynamic colors for border-left (Tailwind arbitrary values in style attribute used instead)
        document.addEventListener('DOMContentLoaded', () => {
            // quick fix for arbitrary colors in renderPhase
            const colorMap = {
                'purple-600': '#9333ea',
                'emerald-600': '#059669',
                'indigo-600': '#4f46e5',
                'amber-600': '#d97706',
                'blue-600': '#2563eb',
                'green-600': '#16a34a'
            };

            // Pre-process phases to add hex color for style attr if needed, 
            // but I used inline style with logic above.

            dashboardManager.init();
        });
    </script>
@endsection