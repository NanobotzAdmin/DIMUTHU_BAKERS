@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-full mx-auto space-y-6" id="incentives-app">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Agent Incentives & Bonuses</h1>
                <p class="text-gray-600">Configure and manage agent incentive schemes</p>
            </div>
            <button onclick="openSchemeModal()"
                class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                <i class="bi bi-plus-lg mr-2"></i>
                New Scheme
            </button>
        </div>

        <!-- Summary Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Active Schemes -->
            <div class="bg-purple-50 p-6 rounded-xl border border-purple-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-trophy text-purple-600 text-xl"></i>
                    <span class="text-purple-700 text-xs font-semibold uppercase tracking-wider">Active Schemes</span>
                </div>
                <p class="text-2xl font-bold text-purple-900" id="stat-active-schemes">0</p>
            </div>

            <!-- Achievements -->
            <div class="bg-green-50 p-6 rounded-xl border border-green-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-award text-green-600 text-xl"></i>
                    <span class="text-green-700 text-xs font-semibold uppercase tracking-wider">Achievements</span>
                </div>
                <p class="text-2xl font-bold text-green-900" id="stat-achievements">0</p>
            </div>

            <!-- Pending Bonus -->
            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-currency-dollar text-yellow-600 text-xl"></i>
                    <span class="text-yellow-700 text-xs font-semibold uppercase tracking-wider">Pending Bonus</span>
                </div>
                <p class="text-xl font-bold text-yellow-900" id="stat-pending-bonus">Rs. 0</p>
            </div>

            <!-- Total Bonus -->
            <div class="bg-blue-50 p-6 rounded-xl border border-blue-200">
                <div class="flex items-center gap-3 mb-2">
                    <i class="bi bi-graph-up text-blue-600 text-xl"></i>
                    <span class="text-blue-700 text-xs font-semibold uppercase tracking-wider">Total Bonus</span>
                </div>
                <p class="text-xl font-bold text-blue-900" id="stat-total-bonus">Rs. 0</p>
            </div>
        </div>

        <!-- Period Selection & Calculate -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                    <input type="month" id="selected-period" onchange="handlePeriodChange()"
                        class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex items-end mt-7">
                    <button onclick="calculateAchievements()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="bi bi-bullseye mr-2"></i>
                        Calculate Achievements
                    </button>
                </div>
            </div>
        </div>

        <!-- Leaderboard -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center gap-3 mb-4">
                <i class="bi bi-trophy-fill text-amber-500 text-xl"></i>
                <h3 class="text-lg font-semibold text-gray-900">Performance Leaderboard</h3>
            </div>

            <div id="leaderboard-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
        </div>

        <!-- Incentive Schemes -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Incentive Schemes</h3>
            <div id="schemes-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
        </div>

        <!-- Pending Achievements -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Achievements</h3>
            <div id="achievements-list" class="space-y-3">
                <!-- Injected JS -->
            </div>
            <div id="no-achievements" class="hidden text-center text-gray-500 py-4">No pending achievements for this period.
            </div>
        </div>

        <!-- Create Scheme Modal -->
        <div id="scheme-modal" class="fixed inset-0 bg-gray-900/75 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Create Incentive Scheme</h3>
                        <p class="text-sm text-gray-500">Set up a new incentive scheme</p>
                    </div>
                    <button onclick="closeSchemeModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <form id="create-scheme-form" onsubmit="handleCreateScheme(event)" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scheme Name</label>
                        <input type="text" id="scheme-name" required placeholder="e.g., Monthly Sales Champion"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="scheme-type"
                                class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="sales_target">Sales Target</option>
                                <option value="accuracy_bonus">Accuracy Bonus</option>
                                <option value="growth_bonus">Growth Bonus</option>
                                <option value="customer_acquisition">Customer Acquisition</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                            <select id="scheme-period"
                                class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Target Value</label>
                            <input type="number" id="scheme-target" required step="0.01" value="100000"
                                class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bonus Amount (Rs.)</label>
                            <input type="number" id="scheme-bonus" required step="0.01" value="5000"
                                class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="scheme-description" rows="3"
                            class="w-full p-2 bg-gray-50 border rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Describe the incentive scheme..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeSchemeModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600">Create
                            Scheme</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Server Data
        const serverAgents = @json($agents ?? []);
        const serverSettlements = @json($settlements ?? []);
    </script>

    <script>
        // State
        const state = {
            agents: serverAgents,
            settlements: serverSettlements,
            schemes: [],
            achievements: [],
            selectedPeriod: ''
        };

        // Defaults
        const defaultSchemes = [
            {
                id: 'scheme_1',
                name: 'Monthly Sales Champion',
                type: 'sales_target',
                period: 'monthly',
                isActive: true,
                targetValue: 400000,
                bonusAmount: 10000,
                description: 'Achieve Rs. 400,000 in monthly sales',
                createdAt: new Date().toISOString()
            },
            {
                id: 'scheme_2',
                name: 'Perfect Accuracy Bonus',
                type: 'accuracy_bonus',
                period: 'monthly',
                isActive: true,
                targetValue: 100,
                bonusAmount: 5000,
                description: 'Maintain 100% cash accuracy for the month',
                createdAt: new Date().toISOString()
            }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            // Init Period (Current Month)
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            state.selectedPeriod = `${yyyy}-${mm}`;
            document.getElementById('selected-period').value = state.selectedPeriod;

            loadData();
            renderAll();
        });

        function loadData() {
            const storedSchemes = localStorage.getItem('incentiveSchemes');
            const storedAchievements = localStorage.getItem('incentiveAchievements');

            if (storedSchemes) {
                state.schemes = JSON.parse(storedSchemes);
            } else {
                state.schemes = defaultSchemes;
                localStorage.setItem('incentiveSchemes', JSON.stringify(defaultSchemes));
            }

            if (storedAchievements) {
                state.achievements = JSON.parse(storedAchievements);
            }
        }

        function saveSchemes(schemes) {
            state.schemes = schemes;
            localStorage.setItem('incentiveSchemes', JSON.stringify(schemes));
            renderAll();
        }

        function saveAchievements(achievements) {
            state.achievements = achievements;
            localStorage.setItem('incentiveAchievements', JSON.stringify(achievements));
            renderAll();
        }

        function handlePeriodChange() {
            state.selectedPeriod = document.getElementById('selected-period').value;
            renderAll();
        }

        // --- Rendering ---
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'LKR', minimumFractionDigits: 0 }).format(amount).replace('LKR', 'Rs.');
        }

        function renderAll() {
            renderStats();
            renderLeaderboard();
            renderSchemes();
            renderAchievements();
        }

        function renderStats() {
            const periodAchievements = state.achievements.filter(a => a.period === state.selectedPeriod);

            document.getElementById('stat-active-schemes').textContent = state.schemes.filter(s => s.isActive).length;
            document.getElementById('stat-achievements').textContent = periodAchievements.length;

            const pending = periodAchievements.filter(a => a.status === 'pending').reduce((sum, a) => sum + Number(a.bonusEarned), 0);
            document.getElementById('stat-pending-bonus').textContent = formatCurrency(pending);

            const total = periodAchievements.reduce((sum, a) => sum + Number(a.bonusEarned), 0);
            document.getElementById('stat-total-bonus').textContent = formatCurrency(total);
        }

        function renderLeaderboard() {
            const list = document.getElementById('leaderboard-list');

            const data = state.agents.map(agent => {
                // Settlements for this month
                const agentSettlements = state.settlements.filter(s =>
                    s.agentId === agent.id && s.settlementDate.startsWith(state.selectedPeriod)
                );
                const totalSales = agentSettlements.reduce((sum, s) => sum + Number(s.totalSales), 0);

                // Achievements for this month
                const agentAch = state.achievements.filter(a =>
                    a.agentId === agent.id && a.period === state.selectedPeriod
                );
                const totalBonus = agentAch.reduce((sum, a) => sum + Number(a.bonusEarned), 0);

                return { agent, totalSales, achievementCount: agentAch.length, totalBonus };
            }).sort((a, b) => b.totalSales - a.totalSales).slice(0, 10);

            list.innerHTML = data.map((entry, index) => `
                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0 font-bold shadow-sm">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-900 font-medium">${entry.agent.agentName}</p>
                        <p class="text-gray-500 text-sm">${entry.agent.agentCode || ''}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-900 font-bold">${formatCurrency(entry.totalSales)}</p>
                        <div class="flex items-center gap-2 justify-end mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="bi bi-star-fill mr-1 text-xs"></i> ${entry.achievementCount}
                            </span>
                            ${entry.totalBonus > 0 ? `
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                +${formatCurrency(entry.totalBonus)}
                            </span>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderSchemes() {
            const list = document.getElementById('schemes-list');
            list.innerHTML = state.schemes.map(s => `
                <div class="p-4 border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h4 class="text-gray-900 font-medium">${s.name}</h4>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium ${s.isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                    ${s.isActive ? 'Active' : 'Inactive'}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                                    ${s.period}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">${s.description}</p>
                            <div class="flex items-center gap-4 text-sm">
                                <span class="text-gray-700">Target: <span class="font-medium">${s.targetValue < 101 && s.type === 'accuracy_bonus' ? s.targetValue + '%' : formatCurrency(s.targetValue)}</span></span>
                                <span class="text-green-600">Bonus: <span class="font-medium">${formatCurrency(s.bonusAmount)}</span></span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="toggleScheme('${s.id}')" class="p-2 text-gray-400 hover:text-gray-600 rounded-full hover:bg-gray-100" title="${s.isActive ? 'Pause' : 'Activate'}">
                                <i class="bi ${s.isActive ? 'bi-pause-circle' : 'bi-play-circle'} text-lg"></i>
                            </button>
                            <button onclick="deleteScheme('${s.id}')" class="p-2 text-red-400 hover:text-red-600 rounded-full hover:bg-red-50" title="Delete">
                                <i class="bi bi-trash text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderAchievements() {
            const list = document.getElementById('achievements-list');
            const empty = document.getElementById('no-achievements');

            const pending = state.achievements.filter(a => a.period === state.selectedPeriod && a.status === 'pending');

            if (pending.length === 0) {
                list.innerHTML = '';
                list.classList.add('hidden');
                empty.classList.remove('hidden');
                return;
            }

            list.classList.remove('hidden');
            empty.classList.add('hidden');

            list.innerHTML = pending.map(a => {
                const agent = state.agents.find(ag => ag.id === a.agentId);
                const scheme = state.schemes.find(s => s.id === a.schemeId);
                const percent = Math.min(100, Math.round(a.achievementPercent));

                return `
                <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex-1 mr-4">
                        <div class="flex justify-between items-center mb-1">
                            <p class="text-gray-900 font-medium">${agent ? agent.agentName : 'Unknown'}</p>
                            <p class="text-green-600 font-bold">+${formatCurrency(a.bonusEarned)}</p>
                        </div>
                        <p class="text-gray-600 text-xs mb-2">${scheme ? scheme.name : 'Unknown Scheme'}</p>

                        <div class="w-full bg-yellow-200 rounded-full h-2 mb-1">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: ${percent}%"></div>
                        </div>
                        <p class="text-gray-500 text-xs text-right">
                            ${percent}% - ${formatCurrency(a.achievedValue)} / ${formatCurrency(a.targetValue)}
                        </p>
                    </div>
                    <div>
                        <button onclick="approveAchievement('${a.id}')" class="px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 flex items-center">
                            <i class="bi bi-check-lg mr-1"></i> Approve
                        </button>
                    </div>
                </div>
                `;
            }).join('');
        }

        // --- Actions ---
        function openSchemeModal() {
            document.getElementById('create-scheme-form').reset();
            document.getElementById('scheme-modal').classList.remove('hidden');
        }

        function closeSchemeModal() {
            document.getElementById('scheme-modal').classList.add('hidden');
        }

        function handleCreateScheme(e) {
            e.preventDefault();

            const newScheme = {
                id: `scheme_${Date.now()}`,
                name: document.getElementById('scheme-name').value,
                type: document.getElementById('scheme-type').value,
                period: document.getElementById('scheme-period').value,
                isActive: true,
                targetValue: Number(document.getElementById('scheme-target').value),
                bonusAmount: Number(document.getElementById('scheme-bonus').value),
                description: document.getElementById('scheme-description').value,
                createdAt: new Date().toISOString()
            };

            saveSchemes([...state.schemes, newScheme]);
            showToast('Scheme created successfully');
            closeSchemeModal();
        }

        function toggleScheme(id) {
            const updated = state.schemes.map(s =>
                s.id === id ? { ...s, isActive: !s.isActive } : s
            );
            saveSchemes(updated);
        }

        function deleteScheme(id) {
            if (!confirm('Are you sure you want to delete this scheme?')) return;
            const updated = state.schemes.filter(s => s.id !== id);
            saveSchemes(updated);
            showToast('Scheme deleted');
        }

        function approveAchievement(id) {
            const updated = state.achievements.map(a =>
                a.id === id ? { ...a, status: 'approved' } : a
            );
            saveAchievements(updated);
            showToast('Achievement approved');
        }

        function calculateAchievements() {
            const period = state.selectedPeriod;
            let newAchievements = [];
            let count = 0;

            // Iterate active schemes
            state.schemes.filter(s => s.isActive).forEach(scheme => {
                // Iterate agents
                state.agents.forEach(agent => {
                    // Check if agent already has this achievement for this period
                    const exists = state.achievements.some(a =>
                        a.agentId === agent.id && a.schemeId === scheme.id && a.period === period
                    );
                    if (exists) return;

                    // Simple Mock Logic based on Scheme Type
                    // In real app, this would aggregate actual settlement data more robustly
                    const periodSettlements = state.settlements.filter(s =>
                        s.agentId === agent.id && s.settlementDate.startsWith(period)
                    );

                    if (periodSettlements.length === 0) return;

                    let achievedValue = 0;
                    let qualified = false;

                    if (scheme.type === 'sales_target') {
                        achievedValue = periodSettlements.reduce((sum, s) => sum + s.totalSales, 0);
                        if (achievedValue >= scheme.targetValue) qualified = true;
                    } else if (scheme.type === 'accuracy_bonus') {
                        // Logic: count variances. 100% means 0 variance occurrences.
                        const varianceCount = periodSettlements.filter(s => Math.abs(s.cashVariance) > 0).length;
                        const totalCount = periodSettlements.length;
                        // achievement % = (1 - variance/total) * 100
                        const accuracy = totalCount > 0 ? ((totalCount - varianceCount) / totalCount) * 100 : 0;
                        achievedValue = accuracy; // e.g. 100
                        if (accuracy >= scheme.targetValue) qualified = true;
                    }

                    if (qualified) {
                        const achievement = {
                            id: `ach_${Date.now()}_${agent.id}_${scheme.id}`,
                            schemeId: scheme.id,
                            agentId: agent.id,
                            period: period,
                            targetValue: scheme.targetValue,
                            achievedValue: achievedValue,
                            achievementPercent: (achievedValue / scheme.targetValue) * 100, // naive percent
                            bonusEarned: scheme.bonusAmount,
                            status: 'pending',
                            achievedAt: new Date().toISOString()
                        };
                        newAchievements.push(achievement);
                        count++;
                    }
                });
            });

            if (count > 0) {
                saveAchievements([...state.achievements, ...newAchievements]);
                showToast(`${count} new achievement(s) recorded`, 'success');
            } else {
                showToast('No new achievements found based on current data.', 'info');
            }
        }

        function showToast(message, type = 'success') {
            const div = document.createElement('div');
            const colorClass = type === 'success' ? 'bg-green-600' : 'bg-blue-600';
            div.className = `fixed top-4 right-4 ${colorClass} text-white px-6 py-3 rounded-lg shadow-lg z-[70] transition-opacity duration-300 transform translate-y-0`;
            div.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-info-circle'} mr-2"></i> ${message}`;
            document.body.appendChild(div);
            setTimeout(() => {
                div.style.opacity = '0';
                setTimeout(() => div.remove(), 300);
            }, 3000);
        }
    </script>
@endsection