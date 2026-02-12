@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg text-white">
                    <i class="bi bi-clock-history text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Allocation History</h1>
                    <p class="text-gray-600 mt-1">View and manage past overhead allocations</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button
                    class="inline-flex items-center px-4 py-3 bg-white border-2 border-gray-200 rounded-xl font-medium text-gray-700 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-download"></i>
                    Export
                </button>
                <a href="{{ url('/overhead/allocation-wizard') }}"
                    class="inline-flex items-center px-4 py-3 bg-gradient-to-br from-purple-500 to-indigo-600 border border-transparent rounded-xl font-medium text-white hover:from-purple-600 hover:to-indigo-700 shadow-md focus:outline-none transition ease-in-out duration-150 gap-2">
                    <i class="bi bi-graph-up-arrow"></i>
                    New Allocation
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-indigo-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600 font-medium">Total</span>
                    <i class="bi bi-clock-history text-indigo-500 text-lg"></i>
                </div>
                <div class="text-3xl font-bold text-indigo-600">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Allocations</div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600 font-medium">Draft</span>
                    <i class="bi bi-file-earmark-text text-gray-500 text-lg"></i>
                </div>
                <div class="text-3xl font-bold text-gray-600">{{ $stats['draft'] }}</div>
                <div class="text-sm text-gray-500 mt-1">In progress</div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600 font-medium">Posted</span>
                    <i class="bi bi-check-circle text-green-500 text-lg"></i>
                </div>
                <div class="text-3xl font-bold text-green-600">{{ $stats['posted'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Completed</div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-red-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600 font-medium">Reversed</span>
                    <i class="bi bi-x-circle text-red-500 text-lg"></i>
                </div>
                <div class="text-3xl font-bold text-red-600">{{ $stats['reversed'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Undone</div>
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600 font-medium">Total Amount</span>
                    <i class="bi bi-currency-dollar text-purple-500 text-lg"></i>
                </div>
                <div class="text-2xl font-bold text-purple-600">Rs {{ number_format($stats['totalAllocated']) }}</div>
                <div class="text-sm text-gray-500 mt-1">Posted allocations</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="searchInput" placeholder="Search by name, period, or creator..."
                    class="pl-10 block w-full h-12 border-gray-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="flex gap-2 overflow-x-auto pb-2 md:pb-0">
                <button onclick="filterStatus('all')"
                    class="filter-btn active group h-12 px-4 rounded-xl flex items-center gap-2 transition-all bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-md border-transparent"
                    data-status="all">
                    <i class="bi bi-funnel"></i>
                    <span class="font-medium">All</span>
                    <span class="bg-white/20 text-white text-xs px-2 py-0.5 rounded-full">{{ $stats['total'] }}</span>
                </button>
                <button onclick="filterStatus('draft')"
                    class="filter-btn group h-12 px-4 rounded-xl flex items-center gap-2 transition-all bg-white text-gray-700 border-2 border-gray-200 hover:border-indigo-300"
                    data-status="draft">
                    <i class="bi bi-funnel"></i>
                    <span class="font-medium">Draft</span>
                    <span
                        class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full">{{ $stats['draft'] }}</span>
                </button>
                <button onclick="filterStatus('posted')"
                    class="filter-btn group h-12 px-4 rounded-xl flex items-center gap-2 transition-all bg-white text-gray-700 border-2 border-gray-200 hover:border-indigo-300"
                    data-status="posted">
                    <i class="bi bi-funnel"></i>
                    <span class="font-medium">Posted</span>
                    <span
                        class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full">{{ $stats['posted'] }}</span>
                </button>
                <button onclick="filterStatus('reversed')"
                    class="filter-btn group h-12 px-4 rounded-xl flex items-center gap-2 transition-all bg-white text-gray-700 border-2 border-gray-200 hover:border-indigo-300"
                    data-status="reversed">
                    <i class="bi bi-funnel"></i>
                    <span class="font-medium">Reversed</span>
                    <span
                        class="bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full">{{ $stats['reversed'] }}</span>
                </button>
            </div>
        </div>

        <!-- Allocation List -->
        <div id="allocationList" class="space-y-4">
            @if(count($executions) > 0)
                @foreach($executions as $execution)
                    <div class="allocation-item bg-white rounded-xl p-6 shadow-sm border-2 border-gray-100 hover:border-indigo-300 transition-all"
                        data-status="{{ $execution['status'] }}" data-name="{{ strtolower($execution['name']) }}"
                        data-period="{{ strtolower($execution['period']) }}"
                        data-creator="{{ strtolower($execution['createdBy']) }}">

                        <div class="flex flex-col md:flex-row items-start justify-between mb-4 gap-4">
                            <div class="flex items-start gap-4 flex-1 w-full">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 text-white
                                        @if($execution['status'] == 'draft') bg-gray-500
                                        @elseif($execution['status'] == 'posted') bg-gradient-to-br from-green-500 to-emerald-600
                                        @elseif($execution['status'] == 'reversed') bg-red-500
                                        @else bg-blue-500 @endif">
                                    <i class="bi 
                                            @if($execution['status'] == 'draft') bi-clock
                                            @elseif($execution['status'] == 'posted') bi-check-circle
                                            @elseif($execution['status'] == 'reversed') bi-x-circle
                                            @else bi-info-circle @endif text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-3 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $execution['name'] }}</h3>
                                        <span class="capitalize px-2.5 py-0.5 rounded-full text-xs font-medium border
                                                @if($execution['status'] == 'draft') bg-gray-100 text-gray-800 border-gray-200
                                                @elseif($execution['status'] == 'posted') bg-green-100 text-green-800 border-green-200
                                                @elseif($execution['status'] == 'reversed') bg-red-100 text-red-800 border-red-200
                                                @else bg-blue-100 text-blue-800 border-blue-200 @endif">
                                            {{ $execution['status'] }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mt-3">
                                        <div><span class="text-gray-500">Period:</span> <span
                                                class="font-medium text-gray-900 ml-1">{{ $execution['period'] }}</span></div>
                                        <div><span class="text-gray-500">Created:</span> <span
                                                class="font-medium text-gray-900 ml-1">{{ $execution['createdAt'] }}</span></div>
                                        <div><span class="text-gray-500">By:</span> <span
                                                class="font-medium text-gray-900 ml-1">{{ $execution['createdBy'] }}</span></div>
                                        <div><span class="text-gray-500">Total:</span> <span
                                                class="font-bold text-purple-600 ml-1">Rs
                                                {{ number_format($execution['totalAllocated']) }}</span></div>
                                    </div>
                                    @if(!empty($execution['notes']))
                                        <div class="mt-3 p-3 bg-gray-50 rounded-lg text-sm text-gray-600 line-clamp-2">
                                            {{ $execution['notes'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right w-full md:w-auto">
                                <div class="text-3xl font-bold text-purple-600 mb-1">Rs
                                    {{ number_format($execution['totalAllocated']) }}</div>
                                <div class="text-sm text-gray-500">{{ count($execution['selectedPools'] ?? []) }} pools •
                                    {{ count($execution['expensesByPool'] ?? []) }} expenses</div>
                            </div>
                        </div>

                        <!-- Breakdown -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div class="p-4 bg-orange-50 rounded-lg border border-orange-100">
                                <div class="text-sm text-gray-600 mb-1">Kitchen</div>
                                <div class="text-xl font-bold text-orange-600">Rs
                                    {{ number_format($execution['allocations']['kitchen']['amount']) }}</div>
                                <div class="text-xs text-gray-600">
                                    {{ number_format($execution['allocations']['kitchen']['percentage'], 1) }}% of total</div>
                            </div>
                            <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <div class="text-sm text-gray-600 mb-1">Cake</div>
                                <div class="text-xl font-bold text-purple-600">Rs
                                    {{ number_format($execution['allocations']['cake']['amount']) }}</div>
                                <div class="text-xs text-gray-600">
                                    {{ number_format($execution['allocations']['cake']['percentage'], 1) }}% of total</div>
                            </div>
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="text-sm text-gray-600 mb-1">Bakery</div>
                                <div class="text-xl font-bold text-blue-600">Rs
                                    {{ number_format($execution['allocations']['bakery']['amount']) }}</div>
                                <div class="text-xs text-gray-600">
                                    {{ number_format($execution['allocations']['bakery']['percentage'], 1) }}% of total</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t-2 border-gray-100">
                            <div class="text-sm text-gray-600">
                                @if($execution['status'] == 'posted' && $execution['postedAt'])
                                    <span class="flex items-center gap-2"><i class="bi bi-check-circle text-green-600"></i> Posted:
                                        {{ $execution['postedAt'] }}</span>
                                @elseif($execution['status'] == 'reversed' && $execution['reversedAt'])
                                    <span class="flex items-center gap-2"><i class="bi bi-exclamation-triangle text-red-600"></i>
                                        Reversed: {{ $execution['reversedAt'] }} by {{ $execution['reversedBy'] }}</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <button onclick="viewDetails('{{ $execution['id'] }}')"
                                    class="h-10 px-4 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-lg flex items-center gap-2 transition-all">
                                    <i class="bi bi-eye"></i> View Details
                                </button>

                                @if($execution['status'] == 'posted')
                                    <button onclick="confirmReverse('{{ $execution['id'] }}')"
                                        class="h-10 px-4 bg-red-50 hover:bg-red-100 text-red-700 border-2 border-red-200 rounded-lg flex items-center gap-2 transition-all">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reverse
                                    </button>
                                @endif

                                @if($execution['status'] == 'draft')
                                    <a href="{{ url('/overhead/allocation-wizard?id=' . $execution['id']) }}"
                                        class="h-10 px-4 bg-purple-50 hover:bg-purple-100 text-purple-700 border-2 border-purple-200 rounded-lg flex items-center gap-2 transition-all">
                                        <i class="bi bi-pencil"></i> Continue
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12 bg-white rounded-xl border-2 border-gray-100">
                    <i class="bi bi-inbox text-4xl text-gray-300"></i>
                    <p class="mt-4 text-gray-500">No allocations found.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500/75 transition-opacity" aria-hidden="true"
                onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white border-b border-gray-100 p-6 flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-900" id="modal-title">Allocation Details</h3>
                    <button type="button" onclick="closeModal()"
                        class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center focus:outline-none">
                        <i class="bi bi-x text-lg text-gray-600"></i>
                    </button>
                </div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto" id="modalContent">
                    <!-- Content will be injected by JS -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data passed from controller
        const executions = @json($executions);

        // Filter Logic
        const searchInput = document.getElementById('searchInput');
        const allocationItems = document.querySelectorAll('.allocation-item');

        function filterItems() {
            const query = searchInput.value.toLowerCase();
            const activeStatus = document.querySelector('.filter-btn.active').dataset.status;

            allocationItems.forEach(item => {
                const status = item.dataset.status;
                const text = item.innerText.toLowerCase();
                const matchesSearch = text.includes(query);
                const matchesStatus = activeStatus === 'all' || status === activeStatus;

                if (matchesSearch && matchesStatus) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        searchInput.addEventListener('input', filterItems);

        function filterStatus(status) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-gradient-to-br', 'from-indigo-500', 'to-purple-600', 'text-white', 'shadow-md', 'border-transparent');
                btn.classList.add('bg-white', 'text-gray-700', 'border-2', 'border-gray-200', 'hover:border-indigo-300');

                // Reset badge styles
                const badge = btn.querySelector('span:last-child');
                if (badge) {
                    badge.className = 'bg-indigo-100 text-indigo-700 text-xs px-2 py-0.5 rounded-full';
                }
            });

            const activeBtn = document.querySelector(`.filter-btn[data-status="${status}"]`);
            activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-gray-200', 'hover:border-indigo-300');
            activeBtn.classList.add('active', 'bg-gradient-to-br', 'from-indigo-500', 'to-purple-600', 'text-white', 'shadow-md', 'border-transparent');

            // Update active badge
            const activeBadge = activeBtn.querySelector('span:last-child');
            if (activeBadge) {
                activeBadge.className = 'bg-white/20 text-white text-xs px-2 py-0.5 rounded-full';
            }

            filterItems();
        }

        // Modal Logic
        function viewDetails(id) {
            const execution = executions.find(e => e.id === id);
            if (!execution) return;

            const modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = `
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Summary</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Name</div>
                            <div class="font-bold text-gray-900">${execution.name}</div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Period</div>
                            <div class="font-bold text-gray-900">${execution.period}</div>
                        </div>
                         <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Status</div>
                            <span class="capitalize font-bold ${getBadgeColor(execution.status)} px-2 py-0.5 rounded-md text-sm border">${execution.status}</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-sm text-gray-600">Total Allocated</div>
                            <div class="font-bold text-purple-600">Rs ${execution.totalAllocated.toLocaleString()}</div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Cost Pools (${execution.expensesByPool ? execution.expensesByPool.length : 0})</h3>
                    <div class="space-y-2">
                        ${execution.expensesByPool ? execution.expensesByPool.map(pool => `
                            <div class="p-4 bg-gray-50 rounded-lg flex items-center justify-between">
                                <div class="font-medium text-gray-900">${pool.poolName}</div>
                                <div class="font-bold text-gray-900">Rs ${pool.amount.toLocaleString()}</div>
                            </div>
                        `).join('') : '<p class="text-gray-500 text-sm">No pools details available.</p>'}
                    </div>
                </div>

                 ${execution.calculatedRates && execution.calculatedRates.length > 0 ? `
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">Overhead Rates</h3>
                    <div class="space-y-2">
                        ${execution.calculatedRates.map(rate => `
                             <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-bold text-gray-900">${rate.poolName}</div>
                                    <div class="text-2xl font-bold text-emerald-600">Rs ${Number(rate.ratePerUnit).toFixed(2)}</div>
                                </div>
                                <div class="text-sm text-gray-600">
                                    ${rate.driverName}: Rs ${rate.totalCost.toLocaleString()} ÷ ${rate.totalVolume.toLocaleString()} = Rs ${Number(rate.ratePerUnit).toFixed(2)} per unit
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>` : ''}

                ${execution.notes ? `
                <div>
                     <h3 class="text-lg font-bold text-gray-900 mb-3">Notes</h3>
                     <div class="p-4 bg-gray-50 rounded-lg text-gray-700">${execution.notes}</div>
                </div>
                ` : ''}
            `;

            document.getElementById('detailsModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        function getBadgeColor(status) {
            switch (status) {
                case 'draft': return 'bg-gray-100 text-gray-800 border-gray-200';
                case 'posted': return 'bg-green-100 text-green-800 border-green-200';
                case 'reversed': return 'bg-red-100 text-red-800 border-red-200';
                default: return 'bg-blue-100 text-blue-800 border-blue-200';
            }
        }

        function confirmReverse(id) {
            if (confirm('Are you sure you want to reverse this allocation? This will undo all overhead allocations for this period.')) {
                // Ideally an API call here. For simulation:
                alert('Allocation reversal initiated for ' + id);
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });

    </script>
@endsection