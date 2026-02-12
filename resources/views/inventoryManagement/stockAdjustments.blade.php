@extends('layouts.app')

@section('content')
    @php
        // --- MOCK DATA ---
        $adjustments = collect([
            (object) [
                'id' => 'adj-1',
                'adjustmentNumber' => 'ADJ-2024-001',
                'date' => '2024-01-15',
                'category' => 'damage',
                'location' => 'Main Bakery',
                'status' => 'pending_approval',
                'totalImpact' => -4500,
                'lines' => [1, 2],
                'notes' => 'Damaged during unloading',
                'createdBy' => 'John Doe',
                'createdAt' => '2024-01-15 10:00:00'
            ],
            (object) [
                'id' => 'adj-2',
                'adjustmentNumber' => 'ADJ-2024-002',
                'date' => '2024-01-12',
                'category' => 'physical_count',
                'location' => 'Warehouse',
                'status' => 'approved',
                'totalImpact' => 1200,
                'lines' => [1],
                'notes' => 'Found extra stock',
                'createdBy' => 'Jane Smith',
                'createdAt' => '2024-01-12 09:00:00',
                'approvedBy' => 'Manager',
                'approvedAt' => '2024-01-12 14:00:00'
            ],
            (object) [
                'id' => 'adj-3',
                'adjustmentNumber' => 'ADJ-2024-003',
                'date' => '2024-01-10',
                'category' => 'spoilage',
                'location' => 'Main Bakery',
                'status' => 'approved',
                'totalImpact' => -850,
                'lines' => [1],
                'notes' => 'Expired yeast',
                'createdBy' => 'John Doe',
                'createdAt' => '2024-01-10 11:30:00',
                'approvedBy' => 'Manager',
                'approvedAt' => '2024-01-10 13:00:00'
            ]
        ]);

        $cycleCounts = collect([
            (object) [
                'id' => 'cc-1',
                'countNumber' => 'CC-2024-001',
                'scheduledDate' => '2024-01-15',
                'frequency' => 'Weekly',
                'location' => 'Main Bakery',
                'assignedTo' => 'John Doe',
                'status' => 'completed',
                'itemsToCount' => 25,
                'itemsCounted' => 25,
                'varianceCount' => 3,
                'progress' => 100,
                'items' => [
                    (object) ['id' => 1, 'productCode' => 'FLR-001', 'productName' => 'Flour (All Purpose)', 'expectedQty' => 50, 'countedQty' => 48, 'unit' => 'kg', 'unitCost' => 150, 'counted' => true],
                    (object) ['id' => 2, 'productCode' => 'SGR-001', 'productName' => 'Sugar', 'expectedQty' => 20, 'countedQty' => 20, 'unit' => 'kg', 'unitCost' => 80, 'counted' => true],
                ]
            ],
            (object) [
                'id' => 'cc-2',
                'countNumber' => 'CC-2024-002',
                'scheduledDate' => '2024-01-22',
                'frequency' => 'Weekly',
                'location' => 'Main Bakery',
                'assignedTo' => 'Jane Smith',
                'status' => 'in_progress',
                'itemsToCount' => 25,
                'itemsCounted' => 18,
                'varianceCount' => 2,
                'progress' => 72,
                'items' => [
                    (object) ['id' => 3, 'productCode' => 'YST-001', 'productName' => 'Yeast', 'expectedQty' => 10, 'countedQty' => null, 'unit' => 'kg', 'unitCost' => 500, 'counted' => false],
                    (object) ['id' => 4, 'productCode' => 'SLT-001', 'productName' => 'Salt', 'expectedQty' => 5, 'countedQty' => 4.5, 'unit' => 'kg', 'unitCost' => 40, 'counted' => true],
                ]
            ]
        ]);

        $stockTakes = collect([
            (object) [
                'id' => 'st-1',
                'takeNumber' => 'ST-2024-Q1',
                'plannedDate' => '2024-03-31',
                'location' => 'Main Bakery',
                'status' => 'completed',
                'assignedUsers' => ['John Doe', 'Jane Smith', 'Mike Johnson'],
                'freezeInventory' => true,
                'totalItems' => 150,
                'countedItems' => 150,
                'varianceItems' => 12,
                'progress' => 100,
                'items' => [
                    (object) ['id' => 101, 'productCode' => 'FLR-001', 'productName' => 'Flour (All Purpose)', 'category' => 'Raw Materials', 'systemQty' => 500, 'countedQty' => 495, 'unit' => 'kg', 'unitCost' => 150, 'counted' => true],
                    (object) ['id' => 102, 'productCode' => 'SGR-001', 'productName' => 'Sugar', 'category' => 'Raw Materials', 'systemQty' => 200, 'countedQty' => 200, 'unit' => 'kg', 'unitCost' => 80, 'counted' => true],
                ]
            ],
            (object) [
                'id' => 'st-2',
                'takeNumber' => 'ST-2024-Q2',
                'plannedDate' => '2024-06-30',
                'location' => 'Main Bakery',
                'status' => 'planned',
                'assignedUsers' => ['John Doe', 'Jane Smith'],
                'freezeInventory' => true,
                'totalItems' => 160,
                'countedItems' => 0,
                'varianceItems' => 0,
                'progress' => 0,
                'items' => [
                    (object) ['id' => 103, 'productCode' => 'EGG-001', 'productName' => 'Eggs (Tray)', 'category' => 'Raw Materials', 'systemQty' => 50, 'countedQty' => null, 'unit' => 'tray', 'unitCost' => 400, 'counted' => false],
                    (object) ['id' => 104, 'productCode' => 'MILK-001', 'productName' => 'Whole Milk', 'category' => 'Raw Materials', 'systemQty' => 100, 'countedQty' => null, 'unit' => 'L', 'unitCost' => 120, 'counted' => false],
                ]
            ]
        ]);

        $stats = [
            'total' => $adjustments->count(),
            'pending' => $adjustments->where('status', 'pending_approval')->count(),
            'approved' => $adjustments->where('status', 'approved')->count(),
            'totalImpact' => $adjustments->where('status', 'approved')->sum('totalImpact')
        ];

        // Helper for formatting currency
        if (!function_exists('formatCurrency')) {
            function formatCurrency($value)
            {
                return 'Rs. ' . number_format(abs($value), 2);
            }
        }
    @endphp

    <div class="p-6 max-w-[1600px] mx-auto space-y-6 bg-gray-50 min-h-screen">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900">
                    <i class="bi bi-exclamation-triangle-fill text-[#D4A017] text-3xl"></i>
                    Stock Adjustments
                </h1>
                <p class="text-gray-600 mt-1">Manage inventory adjustments, cycle counts, and physical stock takes</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="toggleModal('varianceReportModal')"
                    class="px-4 py-2 border border-[#D4A017] text-[#D4A017] rounded-md hover:bg-[#D4A017]/10 flex items-center transition-colors font-medium text-sm">
                    <i class="bi bi-bar-chart-line mr-2"></i>
                    Variance Report
                </button>
                <button onclick="toggleModal('createAdjustmentModal')"
                    class="px-4 py-2 bg-[#D4A017] text-white rounded-md hover:bg-[#B8860B] flex items-center transition-colors font-medium text-sm shadow-sm">
                    <i class="bi bi-plus-lg mr-2"></i>
                    New Adjustment
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-6 rounded-xl border-gray-200 border shadow-sm">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Adjustments</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</h3>
                <p class="text-xs text-gray-400 mt-2">All time</p>
            </div>

            <div class="bg-white p-6 rounded-xl border-gray-200 border shadow-sm">
                <p class="text-sm text-gray-500 font-medium mb-1">Pending Approval</p>
                <h3 class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</h3>
                <p class="text-xs text-gray-400 mt-2">Awaiting review</p>
            </div>

            <div class="bg-white p-6 rounded-xl border-gray-200 border shadow-sm">
                <p class="text-sm text-gray-500 font-medium mb-1">Approved</p>
                <h3 class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</h3>
                <p class="text-xs text-gray-400 mt-2">Posted to inventory</p>
            </div>

            <div class="bg-white p-6 rounded-xl border-gray-200 border shadow-sm">
                <p class="text-sm text-gray-500 font-medium mb-1">Total Impact (Loss)</p>
                <h3 class="text-3xl font-bold text-red-600">{{ formatCurrency($stats['totalImpact']) }}</h3>
                <p class="text-xs text-gray-400 mt-2">Inventory value lost</p>
            </div>
        </div>

        <!-- Main Content Tabs -->
        <div class="bg-gray-100 p-1 rounded-full flex gap-1 mb-4">
            <button onclick="switchTab('adjustments')" id="tab-btn-adjustments"
                class="tab-btn flex-1 py-2 px-4 rounded-full text-sm font-medium transition-all bg-white shadow-sm text-gray-900">
                <i class="bi bi-file-text mr-2"></i>Adjustments
            </button>
            <button onclick="switchTab('cycle-counts')" id="tab-btn-cycle-counts"
                class="tab-btn flex-1 py-2 px-4 rounded-full text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-white/50">
                <i class="bi bi-graph-up mr-2"></i>Cycle Counts
            </button>
            <button onclick="switchTab('stock-takes')" id="tab-btn-stock-takes"
                class="tab-btn flex-1 py-2 px-4 rounded-full text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-white/50">
                <i class="bi bi-clipboard-check mr-2"></i>Physical Stock Takes
            </button>
            <button onclick="switchTab('audit-trail')" id="tab-btn-audit-trail"
                class="tab-btn flex-1 py-2 px-4 rounded-full text-sm font-medium transition-all text-gray-500 hover:text-gray-700 hover:bg-white/50">
                <i class="bi bi-eye mr-2"></i>Audit Trail
            </button>
        </div>

        <!-- Tab Content: Adjustments -->
        <div id="tab-content-adjustments" class="tab-content space-y-4">
            <div class="bg-white p-4 rounded-xl border-gray-200 border shadow-sm grid grid-cols-12 gap-4">
                <div class="col-span-3 relative">
                    <i class="bi bi-search absolute left-3 top-2.5 text-gray-400"></i>
                    <input type="text" placeholder="Search adjustments..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-md focus:ring-2 focus:ring-[#D4A017] focus:border-[#D4A017] outline-none text-sm transition-all bg-gray-100">
                </div>
                <div class="col-span-2">
                    <select
                        class="w-full p-2 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#D4A017] outline-none bg-gray-100">
                        <option value="all">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="pending_approval">Pending Approval</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <select
                        class="w-full p-2 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#D4A017] outline-none bg-gray-100">
                        <option value="all">All Categories</option>
                        <option value="physical_count">Physical Count</option>
                        <option value="damage">Damage</option>
                        <option value="spoilage">Spoilage</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <select
                        class="w-full p-2 border border-gray-200 rounded-md text-sm focus:ring-2 focus:ring-[#D4A017] outline-none bg-gray-100">
                        <option value="all">All Locations</option>
                        <option value="Main Bakery">Main Bakery</option>
                        <option value="Warehouse">Warehouse</option>
                    </select>
                </div>
                <div class="col-span-1.5">
                    <input type="date"
                        class="w-full p-2 border border-gray-200 rounded-md text-sm outline-none bg-gray-100">
                </div>
                <div class="col-span-1.5">
                    <input type="date"
                        class="w-full p-2 border border-gray-200 rounded-md text-sm outline-none bg-gray-100">
                </div>
            </div>

            <div class="bg-white rounded-xl border-gray-200 border shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-900">Adjustments ({{ $stats['total'] }})</h3>
                    <p class="text-sm text-gray-500">View and manage all stock adjustments</p>
                </div>
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200 font-medium text-gray-700">
                        <tr>
                            <th class="px-6 py-3">Date</th>
                            <th class="px-6 py-3">Adj #</th>
                            <th class="px-6 py-3">Category</th>
                            <th class="px-6 py-3">Location</th>
                            <th class="px-6 py-3 text-right">Lines</th>
                            <th class="px-6 py-3 text-right">Impact</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($adjustments as $adj)
                            @php
                                $catColors = [
                                    'damage' => 'bg-orange-500',
                                    'physical_count' => 'bg-blue-500',
                                    'spoilage' => 'bg-red-500'
                                ];
                                $catLabel = ucfirst(str_replace('_', ' ', $adj->category));

                                $statusColors = [
                                    'pending_approval' => 'bg-yellow-500',
                                    'approved' => 'bg-green-500',
                                    'rejected' => 'bg-red-500'
                                ];
                                $statusLabel = ucfirst(str_replace('_', ' ', $adj->status));
                            @endphp
                            <tr class="hover:bg-gray-50 group transition-colors">
                                <td class="px-6 py-3 text-gray-600">{{ $adj->date }}</td>
                                <td class="px-6 py-3 font-mono text-blue-600 font-semibold">{{ $adj->adjustmentNumber }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="{{ $catColors[$adj->category] ?? 'bg-gray-500' }} text-white px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center">
                                        <i class="bi bi-tag-fill mr-1.5 text-[0.6rem]"></i>{{ $catLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-600">{{ $adj->location }}</td>
                                <td class="px-6 py-3 text-right text-gray-500">{{ count($adj->lines) }} items</td>
                                <td
                                    class="px-6 py-3 text-right font-medium {{ $adj->totalImpact < 0 ? 'text-red-600' : 'text-green-600' }}">
                                    <div class="flex items-center justify-end gap-1">
                                        <i
                                            class="bi {{ $adj->totalImpact < 0 ? 'bi-graph-down-arrow' : 'bi-graph-up-arrow' }}"></i>
                                        {{ formatCurrency($adj->totalImpact) }}
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    <span
                                        class="{{ $statusColors[$adj->status] ?? 'bg-gray-500' }} text-white px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center">
                                        <i class="bi bi-circle-fill mr-1.5 text-[0.6rem]"></i>{{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="opacity-100 group-hover:opacity-100 transition-opacity flex justify-end gap-2">
                                        <button onclick="viewAdjustment('{{ $adj->id }}')"
                                            class="p-1 px-3 rounded hover:bg-gray-100 text-sm font-medium text-gray-600 flex items-center">
                                            <i class="bi bi-eye mr-2"></i>View
                                        </button>
                                        @if($adj->status == 'pending_approval')
                                            <button
                                                class="p-1 px-3 rounded hover:bg-green-50 text-sm font-medium text-green-600 flex items-center">
                                                <i class="bi bi-check-circle mr-2"></i>Approve
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content: Cycle Counts -->
        <div id="tab-content-cycle-counts" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-xl border-gray-200 border shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Cycle Counting</h3>
                        <p class="text-sm text-gray-500">Regular, periodic counting of selected inventory items</p>
                    </div>
                    <button onclick="toggleModal('createCycleCountModal')"
                        class="bg-[#D4A017] text-white px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-[#B8860B] transition flex items-center gap-2 text-sm">
                        <i class="bi bi-plus-lg"></i> Schedule Cycle Count
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach($cycleCounts as $cc)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-bold text-lg text-gray-900">{{ $cc->countNumber }}</h4>
                                        <span
                                            class="bg-blue-500 text-white px-2.5 py-1 rounded-full text-xs font-medium">{{ ucfirst(str_replace('_', ' ', $cc->status)) }}</span>
                                        @if($cc->varianceCount > 0)
                                            <span
                                                class="border border-red-500 text-red-600 px-2.5 py-1 rounded-full text-xs font-bold">{{ $cc->varianceCount }}
                                                variance(s)</span>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-4 gap-8 text-sm text-gray-600 mb-3">
                                        <div><span
                                                class="block text-gray-400 text-xs uppercase font-bold">Scheduled</span>{{ $cc->scheduledDate }}
                                        </div>
                                        <div><span
                                                class="block text-gray-400 text-xs uppercase font-bold">Location</span>{{ $cc->location }}
                                        </div>
                                        <div><span class="block text-gray-400 text-xs uppercase font-bold">Assigned
                                                To</span>{{ $cc->assignedTo }}</div>
                                        <div><span
                                                class="block text-gray-400 text-xs uppercase font-bold">Progress</span>{{ $cc->itemsCounted }}
                                            / {{ $cc->itemsToCount }} items</div>
                                    </div>
                                    @if($cc->status == 'in_progress')
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2 max-w-md">
                                            <div class="bg-[#D4A017] h-1.5 rounded-full" style="width: {{ $cc->progress }}%"></div>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <button onclick="executeCycleCount('{{ $cc->id }}')"
                                        class="border border-gray-300 rounded-md px-3 py-1.5 text-sm font-medium hover:bg-gray-50">Start
                                        / Details</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tab Content: Stock Takes -->
        <div id="tab-content-stock-takes" class="tab-content hidden space-y-4">
            <div class="bg-white rounded-xl border-gray-200 border shadow-sm p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Physical Stock Takes</h3>
                        <p class="text-sm text-gray-500">Comprehensive inventory counts, typically quarterly or annually</p>
                    </div>
                    <button onclick="toggleModal('createStockTakeModal')"
                        class="bg-[#D4A017] text-white px-4 py-2 rounded-lg font-bold shadow-sm hover:bg-[#B8860B] transition flex items-center gap-2 text-sm">
                        <i class="bi bi-plus-lg"></i> Plan Stock Take
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach($stockTakes as $st)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <h4 class="font-bold text-lg text-gray-900">{{ $st->takeNumber }}</h4>
                                        <span
                                            class="bg-blue-500 text-white px-2.5 py-1 rounded-full text-xs font-medium">{{ ucfirst(str_replace('_', ' ', $st->status)) }}</span>
                                        @if($st->freezeInventory)
                                            <span
                                                class="border border-blue-500 text-blue-600 px-2.5 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                                <i class="bi bi-lock-fill"></i> Inventory Frozen
                                            </span>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-4 gap-8 text-sm text-gray-600 mb-3">
                                        <div><span class="block text-gray-400 text-xs uppercase font-bold">Planned
                                                Date</span>{{ $st->plannedDate }}</div>
                                        <div><span
                                                class="block text-gray-400 text-xs uppercase font-bold">Location</span>{{ $st->location }}
                                        </div>
                                        <div><span
                                                class="block text-gray-400 text-xs uppercase font-bold">Team</span>{{ count($st->assignedUsers) }}
                                            users</div>
                                        <div><span
                                                class="block text-gray-400 text-xs uppercase font-bold">Progress</span>{{ $st->countedItems }}
                                            / {{ $st->totalItems }} items</div>
                                    </div>
                                    @if($st->status == 'in_progress')
                                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2 max-w-md">
                                            <div class="bg-[#D4A017] h-1.5 rounded-full" style="width: {{ $st->progress }}%"></div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="executeStockTake('{{ $st->id }}')"
                                        class="bg-[#D4A017] text-white rounded-md px-3 py-1.5 text-sm font-medium hover:bg-[#B8860B]">Start
                                        Stock Take</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Tab Content: Audit Trail -->
        <div id="tab-content-audit-trail" class="tab-content hidden">
            <div class="bg-white rounded-xl border-gray-200 border shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Adjustment Audit Trail</h3>
                    <p class="text-sm text-gray-500">Complete history of all stock adjustments</p>
                </div>

                <div class="space-y-4">
                    @foreach($adjustments as $adj)
                        <div
                            class="border-l-4 border-[#D4A017] pl-4 py-2 bg-white rounded-r-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="font-semibold text-sm text-gray-900">
                                        {{ $adj->adjustmentNumber }} - {{ ucfirst(str_replace('_', ' ', $adj->category)) }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1">
                                        Created by {{ $adj->createdBy }} on
                                        {{ \Carbon\Carbon::parse($adj->createdAt)->format('d/m/Y, H:i:s') }}
                                    </div>
                                    @if(isset($adj->approvedBy))
                                        <div class="text-sm text-green-600 mt-1 flex items-center gap-1">
                                            <i class="bi bi-check2"></i> Approved by {{ $adj->approvedBy }} on
                                            {{ \Carbon\Carbon::parse($adj->approvedAt)->format('d/m/Y, H:i:s') }}
                                        </div>
                                    @endif
                                    @if(isset($adj->rejectedBy))
                                        <div class="text-sm text-red-600 mt-1 flex items-center gap-1">
                                            <i class="bi bi-x"></i> Rejected by {{ $adj->rejectedBy }}: {{ $adj->rejectionReason }}
                                        </div>
                                    @endif
                                </div>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $adj->status === 'approved' ? 'bg-green-100 text-green-800' : ($adj->status === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $adj->status)) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 mt-2">
                                Impact: <span
                                    class="font-mono font-medium {{ $adj->totalImpact < 0 ? 'text-red-500' : 'text-green-500' }}">{{ formatCurrency($adj->totalImpact) }}</span>
                                ({{ count($adj->lines) }} item{{ count($adj->lines) !== 1 ? 's' : '' }})
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Create Adjustment Modal -->
        <div id="createAdjustmentModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div
                        class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl">
                        <!-- Modal Header -->
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Create Stock
                                        Adjustment</h3>
                                    <p class="text-sm text-gray-500 mt-1">Record inventory changes with reasons</p>
                                </div>
                                <button type="button" onclick="toggleModal('createAdjustmentModal')"
                                    class="text-gray-400 hover:text-gray-500 transition-colors">
                                    <i class="bi bi-x-lg text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-gray-50/50 px-4 py-5 sm:p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                            <!-- Form Header -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Date <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" value="{{ date('Y-m-d') }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Category <span
                                            class="text-red-500">*</span></label>
                                    <select
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                        <option value="physical_count">Physical Count</option>
                                        <option value="damage">Damage</option>
                                        <option value="spoilage">Spoilage</option>
                                        <option value="theft">Theft</option>
                                        <option value="expired">Expired</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Location <span
                                            class="text-red-500">*</span></label>
                                    <select
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                        <option>Main Bakery</option>
                                        <option>Branch 1</option>
                                        <option>Warehouse</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Main Form Area -->
                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                                <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                                    <h4 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Adjustment Lines
                                    </h4>
                                    <button onclick="addLine()"
                                        class="text-xs bg-white border hover:bg-gray-50 px-3 py-1.5 rounded-md font-medium transition-colors shadow-sm text-gray-700">
                                        <i class="bi bi-plus-lg mr-1"></i> Add Line
                                    </button>
                                </div>

                                <!-- Table Header -->
                                <div
                                    class="grid grid-cols-12 gap-4 px-4 py-2 bg-gray-50 border-b border-gray-200 text-xs font-bold text-gray-500 uppercase tracking-wider">
                                    <div class="col-span-3">Product</div>
                                    <div class="col-span-1 text-right">Current</div>
                                    <div class="col-span-2">Adjusted Qty</div>
                                    <div class="col-span-1 text-right">Variance</div>
                                    <div class="col-span-1 text-right">Unit Cost</div>
                                    <div class="col-span-1 text-right">Impact</div>
                                    <div class="col-span-3">Reason</div>
                                </div>

                                <!-- Lines Container -->
                                <div id="lines-container">
                                    <!-- Line Item Template (First Row) -->
                                    <div
                                        class="grid grid-cols-12 gap-4 px-4 py-3 border-b items-center hover:bg-gray-50 transition-colors group">
                                        <div class="col-span-3">
                                            <select
                                                class="block w-full rounded-md border-gray-300 text-sm p-1.5 border focus:ring-1 focus:ring-[#D4A017] outline-none">
                                                <option>FLR-001 - Flour (All Purpose)</option>
                                                <option>SGR-001 - Sugar</option>
                                            </select>
                                        </div>
                                        <div class="col-span-1 text-right font-mono text-sm text-gray-600">50.0</div>
                                        <div class="col-span-2">
                                            <input type="number"
                                                class="block w-full rounded-md border-gray-300 text-sm p-1.5 border text-right focus:ring-1 focus:ring-[#D4A017] outline-none"
                                                placeholder="0.00">
                                        </div>
                                        <div class="col-span-1 text-right font-mono text-sm font-bold text-gray-400">-</div>
                                        <div class="col-span-1 text-right font-mono text-sm text-gray-600">150.00</div>
                                        <div class="col-span-1 text-right font-mono text-sm font-bold text-gray-400">-</div>
                                        <div class="col-span-3 flex gap-2">
                                            <input type="text"
                                                class="block w-full rounded-md border-gray-300 text-sm p-1.5 border focus:ring-1 focus:ring-[#D4A017] outline-none"
                                                placeholder="Reason...">
                                            <button class="text-gray-300 hover:text-red-500 transition-colors"><i
                                                    class="bi bi-trash"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Totals Footer -->
                                <div class="px-4 py-3 bg-gray-50 flex justify-between items-center border-t border-gray-200">
                                    <span class="text-sm font-medium text-gray-600">Total Financial Impact:</span>
                                    <span class="text-lg font-bold text-gray-900">Rs. 0.00</span>
                                </div>
                            </div>

                            <!-- Notes & Upload -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Additional Notes</label>
                                    <textarea rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border"
                                        placeholder="Any extra details..."></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-semibold text-gray-700">Documentation</label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#D4A017] hover:bg-amber-50/10 transition-colors cursor-pointer group">
                                        <i
                                            class="bi bi-camera text-2xl text-gray-400 group-hover:text-[#D4A017] transition-colors"></i>
                                        <p class="text-sm text-gray-600 mt-1 font-medium">Upload photos</p>
                                        <p class="text-xs text-gray-400">Proof of damage, leakage, etc.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                            <button type="button"
                                class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto transition-colors">Create
                                Adjustment</button>
                            <button type="button" onclick="toggleModal('createAdjustmentModal')"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- View Adjustment Modal -->
    <div id="viewAdjustmentModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl flex flex-col max-h-[90vh]">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold leading-6 text-gray-900">Adjustment Details</h3>
                                <p class="text-sm text-gray-500 mt-1" id="view-adj-number">ADJ-XXXX-XXX</p>
                            </div>
                            <button type="button" onclick="toggleModal('viewAdjustmentModal')"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-gray-50/50 px-4 py-5 sm:p-6 space-y-6 overflow-y-auto flex-1">
                        <!-- Header Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Date</p>
                                <p class="font-medium text-gray-900" id="view-adj-date">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Category</p>
                                <span id="view-adj-category"
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                    --
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Location</p>
                                <p class="font-medium text-gray-900" id="view-adj-location">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span id="view-adj-status"
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                    --
                                </span>
                            </div>
                        </div>

                        <!-- Lines -->
                        <div>
                            <h4 class="font-semibold mb-2 text-gray-900">Adjustment Lines</h4>
                            <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                <div
                                    class="grid grid-cols-12 gap-2 px-3 py-2 bg-gray-50 border-b border-gray-200 font-medium text-xs text-gray-500 uppercase tracking-wider">
                                    <div class="col-span-3">Product</div>
                                    <div class="col-span-1 text-right">Current</div>
                                    <div class="col-span-1 text-right">Adjusted</div>
                                    <div class="col-span-1 text-right">Variance</div>
                                    <div class="col-span-2 text-right">Unit Cost</div>
                                    <div class="col-span-2 text-right">Impact</div>
                                    <div class="col-span-2">Reason</div>
                                </div>
                                <div id="view-lines-container">
                                    <!-- Dynamic lines will go here -->
                                </div>
                                <div class="px-3 py-3 bg-gray-50 border-t ">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-sm text-gray-700">Total Impact:</span>
                                        <span class="text-lg font-bold" id="view-total-impact">Rs. 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="p-4 bg-gray-50 rounded-lg hidden" id="view-notes-container">
                            <p class="text-sm text-gray-600 mb-1 font-semibold">Notes</p>
                            <p class="text-sm text-gray-800" id="view-notes">--</p>
                        </div>

                        <!-- Metadata -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg text-xs text-gray-600">
                            <div>
                                <p>Created By</p>
                                <p class="font-medium text-gray-800" id="view-created-by">--</p>
                                <p class="mt-0.5" id="view-created-at">--</p>
                            </div>
                            <div id="view-approved-container" class="hidden">
                                <p>Approved By</p>
                                <p class="font-medium text-gray-800" id="view-approved-by">--</p>
                                <p class="mt-0.5" id="view-approved-at">--</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200">
                        <button type="button" onclick="toggleModal('viewAdjustmentModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Execute Cycle Count Modal -->
    <div id="executeCycleCountModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl flex flex-col max-h-[90vh]">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold leading-6 text-gray-900">Execute Cycle Count</h3>
                                <p class="text-sm text-gray-500 mt-1" id="exec-cc-number">CC-XXXX-XXX</p>
                            </div>
                            <button type="button" onclick="toggleModal('executeCycleCountModal')"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-gray-50/50 px-4 py-5 sm:p-6 space-y-6 overflow-y-auto flex-1">
                        <!-- Header Info -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Location</p>
                                <p class="font-medium text-gray-900" id="exec-cc-location">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Scheduled Date</p>
                                <p class="font-medium text-gray-900" id="exec-cc-date">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Assigned To</p>
                                <p class="font-medium text-gray-900" id="exec-cc-assigned">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Progress</p>
                                <p class="font-medium text-gray-900" id="exec-cc-progress">-- / --</p>
                            </div>
                        </div>

                        <!-- Count Items Table -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                            <div
                                class="grid grid-cols-12 gap-2 px-3 py-2 bg-gray-50 border-b border-gray-200 font-medium text-xs text-gray-500 uppercase tracking-wider">
                                <div class="col-span-3">Product</div>
                                <div class="col-span-1 text-right">Expected</div>
                                <div class="col-span-2">Counted Qty</div>
                                <div class="col-span-1 text-right">Variance</div>
                                <div class="col-span-1 text-right">Unit Cost</div>
                                <div class="col-span-2 text-right">Impact</div>
                                <div class="col-span-2 text-center">Status</div>
                            </div>
                            <div id="exec-cc-lines-container">
                                <!-- Dynamic lines go here -->
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                        <button type="button"
                            class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto transition-colors">
                            <i class="bi bi-check-circle mr-2"></i> Complete Count
                        </button>
                        <button type="button" onclick="toggleModal('executeCycleCountModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Save
                            & Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Execute Stock Take Modal -->
    <div id="executeStockTakeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl flex flex-col max-h-[90vh]">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold leading-6 text-gray-900">Execute Stock Take</h3>
                                <p class="text-sm text-gray-500 mt-1" id="exec-st-number">ST-XXXX-XX</p>
                            </div>
                            <button type="button" onclick="toggleModal('executeStockTakeModal')"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-gray-50/50 px-4 py-5 sm:p-6 space-y-6 overflow-y-auto flex-1">
                        <!-- Header Info -->
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-600">Location</p>
                                <p class="font-medium text-gray-900" id="exec-st-location">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Planned Date</p>
                                <p class="font-medium text-gray-900" id="exec-st-date">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Team Members</p>
                                <p class="font-medium text-gray-900" id="exec-st-team">--</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Progress</p>
                                <p class="font-medium text-gray-900" id="exec-st-progress">-- / --</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <span id="exec-st-status"
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-blue-700 border border-blue-200 bg-blue-50 mt-1">
                                    <i class="bi bi-lock-fill mr-1"></i> Frozen
                                </span>
                            </div>
                        </div>

                        <!-- Count Items Table -->
                        <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                            <div
                                class="grid grid-cols-12 gap-2 px-3 py-2 bg-gray-50 border-b border-gray-200 font-medium text-xs text-gray-500 uppercase tracking-wider">
                                <div class="col-span-3">Product</div>
                                <div class="col-span-1 text-right">System Qty</div>
                                <div class="col-span-2">Counted Qty</div>
                                <div class="col-span-1 text-right">Variance</div>
                                <div class="col-span-1 text-right">Unit Cost</div>
                                <div class="col-span-2 text-right">Impact</div>
                                <div class="col-span-2 text-center">Status</div>
                            </div>
                            <div id="exec-st-lines-container">
                                <!-- Dynamic lines go here -->
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                        <button type="button"
                            class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto transition-colors">
                            <i class="bi bi-check-circle mr-2"></i> Complete Stock Take
                        </button>
                        <button type="button" onclick="toggleModal('executeStockTakeModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Save
                            & Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Variance Report Modal (Placeholder) -->
    <div id="varianceReportModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-6xl flex flex-col max-h-[90vh]">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold leading-6 text-gray-900">Stock Variance Report</h3>
                                <p class="text-sm text-gray-500 mt-1">Comprehensive analysis of stock adjustments and their
                                    financial impact</p>
                            </div>
                            <button type="button" onclick="toggleModal('varianceReportModal')"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="bg-gray-50/50 px-4 py-5 sm:p-6 space-y-6 overflow-y-auto flex-1">
                        <div class="text-center py-12">
                            <i class="bi bi-bar-chart-line text-6xl text-gray-300 mb-4 block"></i>
                            <h3 class="text-lg font-semibold mb-2 text-gray-900">Variance Report</h3>
                            <p class="text-gray-600">Detailed variance analysis and reporting coming soon</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                        <button type="button" onclick="toggleModal('varianceReportModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Stock Take Modal -->
    <div id="createStockTakeModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold leading-6 text-gray-900">Plan Physical Stock Take</h3>
                                <p class="text-sm text-gray-500 mt-1">Schedule a comprehensive count of all inventory items
                                </p>
                            </div>
                            <button type="button" onclick="toggleModal('createStockTakeModal')"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Planned Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Location <span
                                        class="text-red-500">*</span></label>
                                <select
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                    <option value="Main Bakery">Main Bakery</option>
                                    <option value="Branch 1">Branch 1</option>
                                    <option value="Warehouse">Warehouse</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Team Members</label>
                            <div class="border rounded-lg p-3 space-y-2 bg-gray-50 max-h-32 overflow-y-auto">
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="user-1"
                                        class="rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="user-1" class="text-sm text-gray-700">John Doe</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="user-2"
                                        class="rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="user-2" class="text-sm text-gray-700">Jane Smith</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="user-3"
                                        class="rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="user-3" class="text-sm text-gray-700">Mike Johnson</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" id="user-4"
                                        class="rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                                    <label for="user-4" class="text-sm text-gray-700">Sarah Williams</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="freezeInventory"
                                class="rounded border-gray-300 text-[#D4A017] focus:ring-[#D4A017]">
                            <label for="freezeInventory" class="text-sm font-medium text-gray-700">Freeze inventory
                                movements during stock take</label>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Notes</label>
                            <textarea rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border"
                                placeholder="Add any notes or instructions..."></textarea>
                        </div>

                        <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">
                            <p class="text-sm text-amber-800">
                                <strong>Important:</strong> Stock takes are comprehensive counts of all items at a location.
                                If "Freeze inventory" is enabled, no stock movements will be allowed during the count
                                period.
                            </p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                        <button type="button"
                            class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto transition-colors">
                            Plan Stock Take
                        </button>
                        <button type="button" onclick="toggleModal('createStockTakeModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Cycle Count Modal -->
    <div id="createCycleCountModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-xl font-bold leading-6 text-gray-900">Schedule Cycle Count</h3>
                                <p class="text-sm text-gray-500 mt-1">Plan a periodic count of selected inventory items</p>
                            </div>
                            <button type="button" onclick="toggleModal('createCycleCountModal')"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i class="bi bi-x-lg text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Scheduled Date <span
                                        class="text-red-500">*</span></label>
                                <input type="date"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Frequency <span
                                        class="text-red-500">*</span></label>
                                <select
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Location <span
                                        class="text-red-500">*</span></label>
                                <select
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                    <option value="Main Bakery">Main Bakery</option>
                                    <option value="Branch 1">Branch 1</option>
                                    <option value="Warehouse">Warehouse</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">Assigned To <span
                                        class="text-red-500">*</span></label>
                                <select
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                    <option value="John Doe">John Doe</option>
                                    <option value="Jane Smith">Jane Smith</option>
                                    <option value="Mike Johnson">Mike Johnson</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Item Category</label>
                            <select
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2.5 border">
                                <option value="all">All Categories</option>
                                <option value="raw-materials">Raw Materials</option>
                                <option value="packaging">Packaging</option>
                                <option value="finished-goods">Finished Goods</option>
                            </select>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> Cycle counting helps maintain inventory accuracy by regularly
                                counting
                                a subset of items. Items will be automatically selected based on value and turnover.
                            </p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-200 gap-2">
                        <button type="button"
                            class="inline-flex w-full justify-center rounded-md bg-[#D4A017] px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-[#B8860B] sm:ml-3 sm:w-auto transition-colors">
                            Schedule Count
                        </button>
                        <button type="button" onclick="toggleModal('createCycleCountModal')"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Vanilla JS Implementation -->
    <script>
        // Pass PHP data to JS
        const mockAdjustments = @json($adjustments);
        const mockStockTakes = @json($stockTakes);
        const mockCycleCounts = @json($cycleCounts);

        // Tab Switching Logic
        function switchTab(tabId) {
            // Build IDs
            const btnId = 'tab-btn-' + tabId;
            const contentId = 'tab-content-' + tabId;

            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'shadow-sm', 'text-gray-900');
                btn.classList.add('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/50');
            });

            // Activate selected button
            const activeBtn = document.getElementById(btnId);
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-500', 'hover:text-gray-700', 'hover:bg-white/50');
                activeBtn.classList.add('bg-white', 'shadow-sm', 'text-gray-900');
            }

            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Show selected content
            const activeContent = document.getElementById(contentId);
            if (activeContent) {
                activeContent.classList.remove('hidden');
            }
        }

        // Modal Logic
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            }
        }

        // View Adjustment Logic
        function viewAdjustment(id) {
            const adj = mockAdjustments.find(a => a.id === id);
            if (!adj) return;

            // Populate basic info
            document.getElementById('view-adj-number').textContent = adj.adjustmentNumber;
            document.getElementById('view-adj-date').textContent = new Date(adj.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            document.getElementById('view-adj-location').textContent = adj.location;

            // Category Badge
            const catEl = document.getElementById('view-adj-category');
            catEl.textContent = adj.category.replace(/_/g, ' ').toUpperCase();
            catEl.className = `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white shadow-sm ` +
                (adj.category === 'damage' ? 'bg-orange-500' :
                    adj.category === 'physical_count' ? 'bg-blue-500' :
                        adj.category === 'spoilage' ? 'bg-red-500' : 'bg-gray-500');

            // Status Badge
            const statusEl = document.getElementById('view-adj-status');
            statusEl.textContent = adj.status.replace(/_/g, ' ').toUpperCase();
            statusEl.className = `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium text-white shadow-sm ` +
                (adj.status === 'pending_approval' ? 'bg-yellow-500' :
                    adj.status === 'approved' ? 'bg-green-500' :
                        adj.status === 'rejected' ? 'bg-red-500' : 'bg-gray-500');

            // Populate Lines (Mock Data for lines as they are just IDs in array above)
            const linesContainer = document.getElementById('view-lines-container');
            linesContainer.innerHTML = '';

            // Create some dummy line data based on the ID to simulate variety
            const dummyLines = [
                { code: 'FLR-001', name: 'Flour (All Purpose)', current: 50.0, adjusted: 48.0, cost: 150.00, reason: 'Spilled bag' },
                { code: 'SGR-001', name: 'Sugar (White)', current: 20.0, adjusted: 22.0, cost: 80.00, reason: 'Found extra' }
            ];

            let totalImpact = 0;

            dummyLines.forEach(line => {
                const variance = line.adjusted - line.current;
                const impact = variance * line.cost;
                totalImpact += impact;

                const html = `
                    <div class="grid grid-cols-12 gap-2 px-3 py-2 border-b border-gray-200 text-sm items-center hover:bg-gray-50">
                        <div class="col-span-3 font-medium text-gray-900">${line.code} - ${line.name}</div>
                        <div class="col-span-1 text-right font-mono text-gray-600">${line.current.toFixed(1)}</div>
                        <div class="col-span-1 text-right font-mono text-gray-900 font-semibold">${line.adjusted.toFixed(1)}</div>
                        <div class="col-span-1 text-right font-mono font-bold ${variance < 0 ? 'text-red-600' : 'text-green-600'}">
                            ${variance > 0 ? '+' : ''}${variance.toFixed(1)}
                        </div>
                        <div class="col-span-2 text-right font-mono text-gray-600">Rs. ${line.cost.toFixed(2)}</div>
                        <div class="col-span-2 text-right font-mono font-bold ${impact < 0 ? 'text-red-600' : 'text-green-600'}">
                            ${impact < 0 ? '-' : '+'}Rs. ${Math.abs(impact).toFixed(2)}
                        </div>
                        <div class="col-span-2 text-gray-500 text-xs italic">${line.reason}</div>
                    </div>
                `;
                linesContainer.insertAdjacentHTML('beforeend', html);
            });

            // Update Total Impact
            const totalEl = document.getElementById('view-total-impact');
            totalEl.textContent = (totalImpact < 0 ? '-' : '+') + 'Rs. ' + Math.abs(totalImpact).toFixed(2);
            totalEl.className = `text-lg font-bold ${totalImpact < 0 ? 'text-red-600' : 'text-green-600'}`;

            // Notes
            const notesContainer = document.getElementById('view-notes-container');
            if (adj.notes) {
                document.getElementById('view-notes').textContent = adj.notes;
                notesContainer.classList.remove('hidden');
            } else {
                notesContainer.classList.add('hidden');
            }

            // Metadata
            document.getElementById('view-created-by').textContent = adj.createdBy;
            document.getElementById('view-created-at').textContent = adj.createdAt;

            const approvedContainer = document.getElementById('view-approved-container');
            if (adj.approvedBy) {
                document.getElementById('view-approved-by').textContent = adj.approvedBy;
                document.getElementById('view-approved-at').textContent = adj.approvedAt;
                approvedContainer.classList.remove('hidden');
            } else {
                approvedContainer.classList.add('hidden');
            }

            toggleModal('viewAdjustmentModal');
        }

        // Execute Cycle Count Logic
        function executeCycleCount(id) {
            const cc = mockCycleCounts.find(c => c.id === id);
            if (!cc) return;

            // Populate Header
            document.getElementById('exec-cc-number').textContent = cc.countNumber;
            document.getElementById('exec-cc-location').textContent = cc.location;
            document.getElementById('exec-cc-date').textContent = new Date(cc.scheduledDate).toLocaleDateString('en-GB');
            document.getElementById('exec-cc-assigned').textContent = cc.assignedTo;
            document.getElementById('exec-cc-progress').textContent = `${cc.itemsCounted} / ${cc.itemsToCount}`;

            const container = document.getElementById('exec-cc-lines-container');
            container.innerHTML = '';

            cc.items.forEach((item, index) => {
                const variance = item.countedQty !== null ? item.countedQty - item.expectedQty : 0;
                const impact = variance * item.unitCost;

                const html = `
                    <div class="grid grid-cols-12 gap-2 px-3 py-3 border-b border-gray-200 items-center hover:bg-gray-50 bg-white" id="cc-row-${item.id}">
                        <div class="col-span-3">
                            <div class="font-medium text-sm text-gray-900">${item.productName}</div>
                            <div class="text-xs text-gray-500">${item.productCode}</div>
                        </div>
                        <div class="col-span-1 text-right font-mono text-sm text-gray-600">
                            ${item.expectedQty} ${item.unit}
                        </div>
                        <div class="col-span-2">
                            <input type="number" 
                                   step="0.1" 
                                   value="${item.countedQty !== null ? item.countedQty : ''}" 
                                   oninput="updateCCLine(this, ${item.expectedQty}, ${item.unitCost}, '${item.id}')"
                                   placeholder="Enter count..." 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-1.5 border text-right font-mono">
                        </div>
                        <div class="col-span-1 text-right font-mono text-sm font-semibold text-gray-600" id="cc-variance-${item.id}">
                            ${item.countedQty !== null ? (variance > 0 ? '+' : '') + variance.toFixed(1) : '-'}
                        </div>
                        <div class="col-span-1 text-right font-mono text-sm text-gray-600">
                            Rs. ${item.unitCost}
                        </div>
                        <div class="col-span-2 text-right font-mono text-sm font-semibold text-gray-600" id="cc-impact-${item.id}">
                            ${item.countedQty !== null ? 'Rs. ' + Math.abs(impact).toFixed(0) : '-'}
                        </div>
                        <div class="col-span-2 text-center">
                            ${item.counted ?
                        '<span class="inline-flex items-center rounded-full bg-green-500 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20"><i class="bi bi-check-circle-fill mr-1"></i>Counted</span>' :
                        '<span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Pending</span>'}
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });

            toggleModal('executeCycleCountModal');
        }

        function updateCCLine(input, expected, cost, itemId) {
            const val = parseFloat(input.value);
            const varianceEl = document.getElementById(`cc-variance-${itemId}`);
            const impactEl = document.getElementById(`cc-impact-${itemId}`);

            if (isNaN(val)) {
                varianceEl.textContent = '-';
                impactEl.textContent = '-';
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-gray-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-gray-600';
                return;
            }

            const variance = val - expected;
            const impact = variance * cost;

            varianceEl.textContent = (variance > 0 ? '+' : '') + variance.toFixed(1);
            impactEl.textContent = 'Rs. ' + Math.abs(impact).toFixed(0);

            // Coloring
            if (variance < 0) {
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-red-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-red-600';
            } else if (variance > 0) {
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-green-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-green-600';
            } else {
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-gray-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-gray-600';
            }
        }

        // Execute Stock Take Logic
        function executeStockTake(id) {
            const st = mockStockTakes.find(s => s.id === id);
            if (!st) return;

            // Populate Header
            document.getElementById('exec-st-number').textContent = st.takeNumber;
            document.getElementById('exec-st-location').textContent = st.location;
            document.getElementById('exec-st-date').textContent = new Date(st.plannedDate).toLocaleDateString('en-GB');
            document.getElementById('exec-st-team').textContent = st.assignedUsers.length + ' assigned';
            document.getElementById('exec-st-progress').textContent = `${st.countedItems} / ${st.totalItems}`;

            const statusEl = document.getElementById('exec-st-status');
            if (st.freezeInventory) {
                statusEl.classList.remove('hidden');
            } else {
                statusEl.classList.add('hidden');
            }

            const container = document.getElementById('exec-st-lines-container');
            container.innerHTML = '';

            st.items.forEach((item, index) => {
                const variance = item.countedQty !== null ? item.countedQty - item.systemQty : 0;
                const impact = variance * item.unitCost;

                const html = `
                    <div class="grid grid-cols-12 gap-2 px-3 py-3 border-b border-gray-200 items-center hover:bg-gray-50 bg-white" id="st-row-${item.id}">
                        <div class="col-span-3">
                            <div class="font-medium text-sm text-gray-900">${item.productName}</div>
                            <div class="text-xs text-gray-500">${item.productCode} • ${item.category}</div>
                        </div>
                        <div class="col-span-1 text-right font-mono text-sm text-gray-600">
                            ${item.systemQty} ${item.unit}
                        </div>
                        <div class="col-span-2">
                            <input type="number" 
                                   step="0.1" 
                                   value="${item.countedQty !== null ? item.countedQty : ''}" 
                                   oninput="updateSTLine(this, ${item.systemQty}, ${item.unitCost}, '${item.id}')"
                                   placeholder="Enter count..." 
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-1.5 border text-right font-mono">
                        </div>
                        <div class="col-span-1 text-right font-mono text-sm font-semibold text-gray-600" id="st-variance-${item.id}">
                            ${item.countedQty !== null ? (variance > 0 ? '+' : '') + variance.toFixed(1) : '-'}
                        </div>
                        <div class="col-span-1 text-right font-mono text-sm text-gray-600">
                            Rs. ${item.unitCost}
                        </div>
                        <div class="col-span-2 text-right font-mono text-sm font-semibold text-gray-600" id="st-impact-${item.id}">
                            ${item.countedQty !== null ? 'Rs. ' + Math.abs(impact).toFixed(0) : '-'}
                        </div>
                        <div class="col-span-2 text-center">
                            ${item.counted ?
                        '<span class="inline-flex items-center rounded-full bg-green-500 px-2 py-1 text-xs font-medium text-white ring-1 ring-inset ring-green-600/20"><i class="bi bi-check-circle-fill mr-1"></i>Counted</span>' :
                        '<span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">Pending</span>'}
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });

            toggleModal('executeStockTakeModal');
        }

        function updateSTLine(input, system, cost, itemId) {
            const val = parseFloat(input.value);
            const varianceEl = document.getElementById(`st-variance-${itemId}`);
            const impactEl = document.getElementById(`st-impact-${itemId}`);

            if (isNaN(val)) {
                varianceEl.textContent = '-';
                impactEl.textContent = '-';
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-gray-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-gray-600';
                return;
            }

            const variance = val - system;
            const impact = variance * cost;

            varianceEl.textContent = (variance > 0 ? '+' : '') + variance.toFixed(1);
            impactEl.textContent = 'Rs. ' + Math.abs(impact).toFixed(0);

            // Coloring
            if (variance < 0) {
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-red-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-red-600';
            } else if (variance > 0) {
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-green-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-green-600';
            } else {
                varianceEl.className = 'col-span-1 text-right font-mono text-sm font-semibold text-gray-600';
                impactEl.className = 'col-span-2 text-right font-mono text-sm font-semibold text-gray-600';
            }
        }

        // Dynamic Form Logic
        function addLine() {
            const container = document.getElementById('lines-container');
            const lineHTML = `
                <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-gray-200 items-center hover:bg-gray-50 transition-colors group">
                    <div class="col-span-3">
                        <select class="block w-full rounded-md border-gray-300 text-sm p-1.5 border focus:ring-1 focus:ring-[#D4A017] outline-none">
                            <option>Select Product...</option>
                            <option>FLR-001 - Flour (All Purpose)</option>
                            <option>SGR-001 - Sugar</option>
                        </select>
                    </div>
                    <div class="col-span-1 text-right font-mono text-sm text-gray-600">0.0</div>
                    <div class="col-span-2">
                        <input type="number" class="block w-full rounded-md border-gray-300 text-sm p-1.5 border text-right focus:ring-1 focus:ring-[#D4A017] outline-none" placeholder="0.00">
                    </div>
                    <div class="col-span-1 text-right font-mono text-sm font-bold text-gray-400">-</div>
                    <div class="col-span-1 text-right font-mono text-sm text-gray-600">0.00</div>
                    <div class="col-span-1 text-right font-mono text-sm font-bold text-gray-400">-</div>
                    <div class="col-span-3 flex gap-2">
                        <input type="text" class="block w-full rounded-md border-gray-300 text-sm p-1.5 border focus:ring-1 focus:ring-[#D4A017] outline-none" placeholder="Reason...">
                        <button onclick="this.closest('.grid').remove()" class="text-gray-300 hover:text-red-500 transition-colors"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', lineHTML);
        }
    </script>
@endsection