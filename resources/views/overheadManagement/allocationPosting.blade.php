@extends('layouts.app')

@section('content')
<div class="p-6 max-w-full mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-800">
            <i class="bi bi-send text-[#D4A017] text-3xl"></i>
            Overhead Allocation Posting
        </h1>
        <p class="text-gray-600 mt-1">
            Post overhead allocations and automatically generate GL journal entries
        </p>
    </div>

    @php
        $postedAllocations = collect($allocations)->where('status', 'posted');
        $draftAllocations = collect($allocations)->whereIn('status', ['draft', 'approved', 'simulated']);
        $allocationsWithJE = collect($allocations)->whereNotNull('glJournalEntryId')->count();
        $allocationsWithoutJE = $postedAllocations->whereNull('glJournalEntryId');
        $totalAllocations = count($allocations);
    @endphp

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Total Allocations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="pb-2">
                <p class="text-sm font-medium text-gray-500">Total Allocations</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $totalAllocations }}</h3>
            </div>
            <p class="text-sm text-gray-500">All allocation executions</p>
        </div>

        <!-- Posted -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="pb-2">
                <p class="text-sm font-medium text-gray-500">Posted</p>
                <h3 class="text-3xl font-bold text-green-600 mt-1">{{ $postedAllocations->count() }}</h3>
            </div>
            <p class="text-sm text-gray-500">Completed allocations</p>
        </div>

        <!-- With Journal Entries -->
        <div class="bg-white rounded-xl shadow-sm border p-6 {{ $allocationsWithJE === $totalAllocations && $totalAllocations > 0 ? 'border-green-200 bg-green-50' : 'border-yellow-200 bg-yellow-50' }}">
            <div class="pb-2">
                <p class="text-sm font-medium text-gray-500">With Journal Entries</p>
                <div class="flex items-center gap-2 mt-1">
                    @if($allocationsWithJE === $totalAllocations && $totalAllocations > 0)
                        <i class="bi bi-check-circle text-green-600 text-2xl"></i>
                    @else
                        <i class="bi bi-exclamation-circle text-yellow-600 text-2xl"></i>
                    @endif
                    <h3 class="text-3xl font-bold text-gray-900">{{ $allocationsWithJE }}/{{ $totalAllocations }}</h3>
                </div>
            </div>
            <p class="text-sm text-gray-500">GL integration status</p>
        </div>

        <!-- Pending JE Generation -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="pb-2">
                <p class="text-sm font-medium text-gray-500">Pending JE Generation</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $allocationsWithoutJE->count() }}</h3>
            </div>
            <p class="text-sm text-gray-500">Posted but no JE</p>
        </div>
    </div>

    <!-- Batch Actions -->
    @if($allocationsWithoutJE->count() > 0)
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl shadow-sm p-6">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-yellow-900 flex items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i>
                    {{ $allocationsWithoutJE->count() }} Posted Allocations Without Journal Entries
                </h3>
                <p class="text-sm text-yellow-700 mt-1">
                    Generate journal entries for allocations that have been posted but don't have GL entries yet
                </p>
            </div>
            <div class="flex items-center gap-4">
                <button onclick="handleBatchGenerateJE({{ $allocationsWithoutJE->count() }})"
                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:outline-none transition ease-in-out duration-150">
                    <i class="bi bi-file-text mr-2"></i>
                    Generate {{ $allocationsWithoutJE->count() }} Journal Entries
                </button>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="batchAutoPost" class="h-4 w-4 text-[#D4A017] focus:ring-[#D4A017] border-gray-300 rounded">
                    <label for="batchAutoPost" class="text-sm font-medium text-gray-700">Auto-post journal entries</label>
                </div>
            </div>
        </div>
    @endif

    <!-- Draft/Pending Allocations -->
    @if($draftAllocations->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Pending Allocations ({{ $draftAllocations->count() }})</h3>
                <p class="text-sm text-gray-500">Allocations ready to be posted</p>
            </div>
            <div class="p-6 space-y-4">
                @foreach($draftAllocations as $allocation)
                    <div class="border rounded-lg p-4 hover:border-gray-300 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $allocation['name'] }}</h4>
                                    <!-- Status Badge -->
                                    @switch($allocation['status'])
                                        @case('posted')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                <i class="bi bi-check-circle mr-1"></i> Posted
                                            </span>
                                            @break
                                        @case('approved')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">Approved</span>
                                            @break
                                        @case('simulated')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">Simulated</span>
                                            @break
                                        @case('reversed')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                <i class="bi bi-x-circle mr-1"></i> Reversed
                                            </span>
                                            @break
                                        @default
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border border-gray-200 text-gray-600">Draft</span>
                                    @endswitch
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-3 text-gray-600">
                                    <div>
                                        <span>Period:</span>
                                        <span class="ml-2 font-medium text-gray-900">{{ $allocation['period'] }}</span>
                                    </div>
                                    <div>
                                        <span>Total:</span>
                                        <span class="ml-2 font-medium text-gray-900">Rs. {{ number_format($allocation['totalAllocated']) }}</span>
                                    </div>
                                    <div>
                                        <span>Pools:</span>
                                        <span class="ml-2 font-medium text-gray-900">{{ $allocation['selectedPools'] }}</span>
                                    </div>
                                    <div>
                                        <span>Created:</span>
                                        <span class="ml-2 font-medium text-gray-900">{{ \Carbon\Carbon::parse($allocation['createdAt'])->format('d/m/Y') }}</span>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 space-y-1 text-sm text-gray-700">
                                    <div class="flex justify-between">
                                        <span>Kitchen:</span>
                                        <span class="font-medium">Rs. {{ number_format($allocation['allocations']['kitchen']['amount']) }} ({{ number_format($allocation['allocations']['kitchen']['percentage'], 1) }}%)</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Cake:</span>
                                        <span class="font-medium">Rs. {{ number_format($allocation['allocations']['cake']['amount']) }} ({{ number_format($allocation['allocations']['cake']['percentage'], 1) }}%)</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Bakery:</span>
                                        <span class="font-medium">Rs. {{ number_format($allocation['allocations']['bakery']['amount']) }} ({{ number_format($allocation['allocations']['bakery']['percentage'], 1) }}%)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ml-4">
                                <button onclick="openPostModal('{{ $allocation['id'] }}')"
                                    class="inline-flex items-center px-4 py-2 bg-[#D4A017] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#B8860B] focus:outline-none transition ease-in-out duration-150">
                                    <i class="bi bi-send mr-2"></i> Post Allocation
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Posted Allocations -->
     <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900">Posted Allocations ({{ $postedAllocations->count() }})</h3>
            <p class="text-sm text-gray-500">Completed overhead allocations with GL integration status</p>
        </div>
        <div class="p-6 space-y-4">
            @forelse($postedAllocations as $allocation)
                <div class="border rounded-lg p-4 hover:border-gray-300 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h4 class="font-semibold text-gray-900">{{ $allocation['name'] }}</h4>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                    <i class="bi bi-check-circle mr-1"></i> Posted
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm mb-3">
                                <div>
                                    <span class="text-gray-600">Period:</span>
                                    <span class="ml-2 font-medium text-gray-900">{{ $allocation['period'] }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total:</span>
                                    <span class="ml-2 font-medium text-gray-900">Rs. {{ number_format($allocation['totalAllocated']) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Posted:</span>
                                    <span class="ml-2 font-medium text-gray-900">{{ $allocation['postedAt'] ? \Carbon\Carbon::parse($allocation['postedAt'])->format('d/m/Y') : '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">By:</span>
                                    <span class="ml-2 font-medium text-gray-900">{{ $allocation['postedBy'] ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-3 space-y-1 text-sm mb-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Kitchen:</span>
                                    <span class="font-medium text-gray-900">Rs. {{ number_format($allocation['allocations']['kitchen']['amount']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Cake:</span>
                                    <span class="font-medium text-gray-900">Rs. {{ number_format($allocation['allocations']['cake']['amount']) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bakery:</span>
                                    <span class="font-medium text-gray-900">Rs. {{ number_format($allocation['allocations']['bakery']['amount']) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($allocation['glJournalEntryId'])
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-check-circle text-green-600"></i>
                                        <span class="text-sm text-green-700">
                                            Journal Entry: {{ $allocation['glJournalEntryId'] }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-exclamation-circle text-yellow-600"></i>
                                        <span class="text-sm text-yellow-700">No journal entry</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2 ml-4">
                            @if($allocation['glJournalEntryId'])
                                <button onclick="window.location.href='#'" title="View Journal Entry"
                                    class="inline-flex items-center p-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none">
                                    <i class="bi bi-book"></i>
                                </button>
                            @endif
                            @if($allocation['status'] !== 'reversed')
                                <button onclick="handleReverseAllocation('{{ $allocation['name'] }}')" title="Reverse Allocation"
                                    class="inline-flex items-center p-2 bg-white border border-gray-300 rounded-md font-semibold text-red-600 hover:text-red-700 hover:bg-red-50 focus:outline-none">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="bi bi-file-text text-5xl opacity-50 mb-4 inline-block"></i>
                    <p>No posted allocations yet</p>
                    <p class="text-sm mt-1">Run the Allocation Wizard to create allocations</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Help Card -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
        <h4 class="text-lg font-bold text-blue-900 mb-2">How Allocation Journal Entry Generation Works</h4>
        <div class="text-sm text-blue-900 space-y-2">
            <p><strong>When you post an overhead allocation:</strong></p>
            <ol class="list-decimal pl-5 space-y-1">
                <li>The allocation distributes overhead costs to Kitchen, Cake, and Bakery departments</li>
                <li>If "Auto-generate journal entry" is enabled, a journal entry is automatically created</li>
                <li>The journal entry debits each department's Manufacturing Overhead Applied account</li>
                <li>The journal entry credits Manufacturing Overhead Control (clearing the overhead pool)</li>
                <li>This moves overhead costs from the control account into department-specific WIP accounts</li>
            </ol>
            <div class="mt-4 pt-4 border-t border-blue-200">
                <p><strong>Example:</strong> Allocating Rs. 150,000 (Kitchen: 60K, Cake: 40K, Bakery: 50K) generates:</p>
                <code class="block mt-2 font-mono text-xs whitespace-pre bg-blue-100 p-2 rounded">
DR: Manufacturing OH Applied - Kitchen  Rs. 60,000
DR: Manufacturing OH Applied - Cake     Rs. 40,000
DR: Manufacturing OH Applied - Bakery   Rs. 50,000
CR: Manufacturing Overhead Control      Rs. 150,000</code>
            </div>
        </div>
    </div>
</div>

<!-- Post Modal -->
<div id="postModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/75 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closePostModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Post Allocation</h3>
                <p class="text-sm text-gray-500 mb-4">Confirm posting of overhead allocation and GL journal entry generation</p>

                <div id="postModalContent" class="bg-gray-50 rounded-lg p-4 mb-4">
                    <!-- Dynamic Details -->
                </div>

                <div class="space-y-3">
                    <h4 class="font-medium text-gray-900">GL Integration Options</h4>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="autoGenerateJE" checked onchange="toggleJEPreview()"
                            class="h-4 w-4 text-[#D4A017] focus:ring-[#D4A017] border-gray-300 rounded">
                        <label for="autoGenerateJE" class="text-sm font-medium text-gray-700">Auto-generate journal entry for allocation</label>
                    </div>

                    <div id="jeOptionsContainer" class="ml-7 space-y-3">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="autoPostJE"
                                class="h-4 w-4 text-[#D4A017] focus:ring-[#D4A017] border-gray-300 rounded">
                            <label for="autoPostJE" class="text-sm font-medium text-gray-700">Auto-post journal entry (otherwise saved as draft)</label>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-900">
                            <p class="font-bold">Journal Entry Preview:</p>
                            <p id="jePreview" class="mt-2 font-mono text-xs whitespace-pre-line"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="confirmPost()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#D4A017] text-base font-medium text-white hover:bg-[#B8860B] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="bi bi-send mr-2"></i> Confirm Post
                </button>
                <button type="button" onclick="closePostModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const allocations = @json($allocations);
    let selectedAllocation = null;

    function openPostModal(id) {
        selectedAllocation = allocations.find(a => a.id === id);
        if(!selectedAllocation) return;

        // Populate details
        document.getElementById('postModalContent').innerHTML = `
            <h4 class="font-medium mb-2 text-gray-900">${selectedAllocation.name}</h4>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <span class="text-gray-600">Period:</span>
                    <span class="ml-2 font-medium text-gray-900">${selectedAllocation.period}</span>
                </div>
                <div>
                    <span class="text-gray-600">Total Amount:</span>
                    <span class="ml-2 font-medium text-gray-900">Rs. ${parseFloat(selectedAllocation.totalAllocated).toLocaleString()}</span>
                </div>
            </div>
        `;

        updateJEPreview();
        document.getElementById('postModal').classList.remove('hidden');
    }

    function closePostModal() {
        document.getElementById('postModal').classList.add('hidden');
        selectedAllocation = null;
    }

    function toggleJEPreview() {
        const isChecked = document.getElementById('autoGenerateJE').checked;
        const subOptions = document.getElementById('jeOptionsContainer');
        if (isChecked) {
            subOptions.classList.remove('hidden');
        } else {
            subOptions.classList.add('hidden');
        }
    }

    function updateJEPreview() {
        if(!selectedAllocation) return;
        const kitchen = selectedAllocation.allocations.kitchen.amount.toLocaleString();
        const cake = selectedAllocation.allocations.cake.amount.toLocaleString();
        const bakery = selectedAllocation.allocations.bakery.amount.toLocaleString();
        const total = parseFloat(selectedAllocation.totalAllocated).toLocaleString();

        const preview = `DR: Manufacturing OH Applied - Kitchen Rs. ${kitchen}
DR: Manufacturing OH Applied - Cake Rs. ${cake}
DR: Manufacturing OH Applied - Bakery Rs. ${bakery}
CR: Manufacturing Overhead Control Rs. ${total}`;

        document.getElementById('jePreview').textContent = preview;
    }

    function confirmPost() {
        if(!selectedAllocation) return;
        
        const autoPost = document.getElementById('autoPostJE').checked;
        const msg = autoPost ? 'Allocation posted and journal entry posted.' : 'Allocation posted and journal entry created as draft.';
        
        closePostModal();
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: msg,
            timer: 2000,
            showConfirmButton: false
        });
    }

    function handleReverseAllocation(name) {
        Swal.fire({
            title: 'Reverse Allocation?',
            text: `Are you sure you want to reverse allocation "${name}"? This will also reverse the journal entry.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reverse it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Reversed!',
                    'Allocation and journal entry have been reversed.',
                    'success'
                )
            }
        })
    }

    function handleBatchGenerateJE(count) {
         Swal.fire({
            icon: 'success',
            title: 'Success',
            text: `Generated ${count} journal entries successfully`,
            timer: 2000,
            showConfirmButton: false
        });
    }
</script>
@endsection
