@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#EDEFF5]">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-[#F59E0B] to-[#D97706] rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                            <i class="bi bi-calendar2-day text-2xl"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-gray-900 text-lg sm:text-2xl font-bold">Agent Loads</h1>
                            <p class="text-gray-500 text-xs sm:text-sm">Manage daily product loads for field agents</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <button onclick="openCreateLoadModal()"
                    class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-colors">
                    <i class="bi bi-plus-lg mr-2"></i>
                    Create Load
                </button>
                </div>
            </div>
        </div>

        <div class="p-6 max-w-[1800px] mx-auto">
        <!-- Search and Actions -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4 justify-between">
                <div class="flex-1 max-w-full">
                    <div class="relative">
                        <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Search loads..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- Loads List -->
        <div class="space-y-4" id="loadsContainer">
            @forelse($loads as $load)
                @php
                    $agentName = 'Unknown';
                    foreach ($agents as $agent) {
                        if ($agent['id'] === $load['agentId']) {
                            $agentName = $agent['agentName'];
                            break;
                        }
                    }
                @endphp
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 load-card transition-all duration-300" 
                     data-search="{{ strtolower($load['loadNumber'] . ' ' . $agentName) }}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-gray-900 font-bold">{{ $load['loadNumber'] }}</h3>
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'loaded' => 'bg-blue-100 text-blue-800',
                                        'in_progress' => 'bg-orange-100 text-orange-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'settled' => 'bg-purple-100 text-purple-800'
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'loaded' => 'Loaded',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'settled' => 'Settled'
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$load['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$load['status']] ?? ucfirst($load['status']) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-4 text-gray-600 text-sm">
                                <div class="flex items-center gap-1">
                                    <i class="bi bi-person"></i>
                                    <span>{{ $agentName }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ date('M j, Y', strtotime($load['loadDate'])) }}</span>
                                </div>
                            </div>
                        </div>

                        @if($load['status'] === 'draft')
                            <button onclick="markLoadAsLoaded({{ $load['id'] }})"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-sm flex items-center shadow-sm transition-colors">
                                <i class="bi bi-check-circle mr-2"></i>
                                Mark as Loaded
                            </button>
                        @endif
                    </div>

                    <!-- Load Stats -->
                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-gray-600 text-xs mb-1 uppercase font-medium">Total Items</p>
                            <p class="text-gray-900 font-medium">{{ count($load['items']) }} products</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs mb-1 uppercase font-medium">Total Quantity</p>
                            <p class="text-gray-900 font-medium">{{ $load['totalQuantity'] }} units</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-xs mb-1 uppercase font-medium">Total Value</p>
                            <p class="text-gray-900 font-medium">Rs. {{ number_format($load['totalValue'], 2) }}</p>
                        </div>
                    </div>

                    <!-- Items Preview -->
                    @if(count($load['items']) > 0)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-gray-700 text-sm font-medium mb-2">Products:</p>
                            <div class="space-y-1">
                                @foreach(array_slice($load['items'], 0, 3) as $item)
                                    <div class="flex justify-between text-sm text-gray-600">
                                        <span>{{ $item['productName'] }}</span>
                                        <span>{{ $item['loadedQuantity'] }} units</span>
                                    </div>
                                @endforeach
                                @if(count($load['items']) > 3)
                                    <p class="text-gray-500 text-xs mt-1">+ {{ count($load['items']) - 3 }} more products</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($load['notes'])
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <p class="text-gray-700 text-sm font-medium mb-1">Notes:</p>
                            <p class="text-gray-600 text-xs italic">{{ $load['notes'] }}</p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                    <i class="bi bi-box-seam text-gray-400 text-6xl mb-4 block"></i>
                    <h3 class="text-gray-900 text-lg font-medium mb-2">No loads found</h3>
                    <p class="text-gray-600 mb-6">Create your first agent load to get started</p>
                    <button onclick="openCreateLoadModal()"
                        class="bg-[#D4A017] hover:bg-[#B8860B] text-white px-4 py-2 rounded-lg inline-flex items-center shadow-sm transition-colors">
                        <i class="bi bi-plus-lg mr-2"></i>
                        Create Load
                    </button>
                </div>
            @endforelse
        </div>
        </div>

        <!-- Create Load Modal -->
        @include('agentDistribution.Modals.createLoad')
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Mark as Loaded Logic
        function markLoadAsLoaded(loadId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to mark this load as Loaded. This cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Mark as Loaded!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("agentDistribution.markAsLoaded") }}',
                        method: 'POST',
                        data: {
                            load_id: loadId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Loaded!',
                                    'The load has been marked as loaded.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON.message || 'Something went wrong.',
                                'error'
                            );
                        }
                    });
                }
            })
        }

        // Search Logic
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchText = this.value.toLowerCase();
            const cards = document.querySelectorAll('.load-card');
            
            cards.forEach(card => {
                const searchData = card.getAttribute('data-search');
                if (searchData.includes(searchText)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
@endsection