@extends('layouts.app')
@section('title', 'Stock Transfers')

@section('content')



<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6 font-sans">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Stock Transfers</h1>
                    <p class="text-gray-600">Manage internal transfers between sections</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 font-medium transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export
                </button>
                <button onclick="window.location.href = '{{ route('createStockTransfer.index') }}'" class="h-12 px-5 bg-gradient-to-br from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    New Transfer
                </button>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-yellow-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Pending</span>
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Awaiting review</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-green-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Approved</span>
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-3xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Ready to move</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">In Transit</span>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                </div>
                <div class="text-3xl font-bold text-blue-600">{{ $stats['inTransit'] }}</div>
                <div class="text-sm text-gray-500 mt-1">Being moved</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-emerald-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Completed Today</span>
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
                <div class="text-3xl font-bold text-emerald-600">{{ $stats['todayCompleted'] }}</div>
                <div class="text-sm text-emerald-500 mt-1">Rs {{ number_format($stats['totalValue']) }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-purple-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-600">Today's Requests</span>
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <div class="text-3xl font-bold text-purple-600">{{ $stats['todayRequests'] }}</div>
                <div class="text-sm text-gray-500 mt-1">New requests</div>
            </div>
        </div>

        {{-- TABS & FILTER --}}
        <div class="flex flex-col lg:flex-row gap-4 mb-4">
            <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide flex-1">
                @php
                    $tabs = [
                        ['id' => 'all', 'label' => 'All Transfers', 'count' => count($transfers)],
                        ['id' => 'pending', 'label' => 'Pending', 'count' => $stats['pending']],
                        ['id' => 'approved', 'label' => 'Approved', 'count' => $stats['approved']],
                        ['id' => 'in-transit', 'label' => 'In Transit', 'count' => $stats['inTransit']],
                        ['id' => 'completed', 'label' => 'Completed', 'count' => $stats['completed']],
                        ['id' => 'history', 'label' => 'History', 'count' => $stats['completed'] + $stats['rejected']],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    <button data-view="{{ $tab['id'] }}" 
                            class="view-tab flex-shrink-0 h-14 px-6 rounded-xl flex items-center justify-center gap-2 transition-all font-medium {{ $tab['id'] === 'all' ? 'bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg active' : 'bg-white text-gray-700 hover:bg-gray-50 border-2 border-gray-200' }}">
                        <span>{{ $tab['label'] }}</span>
                        @if($tab['count'] > 0)
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $tab['id'] === 'all' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-700' }}">{{ $tab['count'] }}</span>
                        @endif
                    </button>
                @endforeach
            </div>

            <div class="bg-white rounded-xl p-4 shadow-sm border-2 border-gray-100 w-full lg:w-96 flex items-center gap-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <input type="text" id="search-input" placeholder="Search transfers..." class="flex-1 text-lg outline-none placeholder:text-gray-300">
            </div>
        </div>
    </div>

    {{-- TRANSFER LIST --}}
    <div class="space-y-3" id="transfer-list">
        @foreach($transfers as $t)
            @php
                // Colors and Icons logic based on Section
                $fromColor = match($t['fromSection']) {
                    'kitchen' => 'from-blue-500 to-blue-600',
                    'cake' => 'from-pink-500 to-pink-600',
                    'bakery' => 'from-amber-500 to-orange-600',
                    default => 'from-gray-500 to-gray-600'
                };
                $toColor = match($t['toSection']) {
                    'kitchen' => 'from-blue-500 to-blue-600',
                    'cake' => 'from-pink-500 to-pink-600',
                    'bakery' => 'from-amber-500 to-orange-600',
                    default => 'from-gray-500 to-gray-600'
                };
                
                // Icons (Using simple SVGs or Emoji for simplicity in loop, ideally mapped)
                $fromIcon = '🏢'; // Default placeholder logic
                $toIcon = '🏢';

                // Status Badge Color
                $statusBadgeClass = match($t['status']) {
                    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-300',
                    'approved' => 'bg-green-100 text-green-700 border-green-300',
                    'in-transit' => 'bg-blue-100 text-blue-700 border-blue-300',
                    'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                    'rejected' => 'bg-red-100 text-red-700 border-red-300',
                    default => 'bg-gray-100 text-gray-700 border-gray-300'
                };

                // Priority Color
                $priorityClass = match($t['priority']) {
                    'urgent' => 'bg-red-500 text-white',
                    'high' => 'bg-orange-500 text-white',
                    'medium' => 'bg-yellow-500 text-white',
                    default => 'bg-gray-500 text-white'
                };
            @endphp

            <div class="transfer-card bg-white rounded-2xl p-5 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all cursor-pointer"
                 data-status="{{ $t['status'] }}"
                 data-search="{{ strtolower($t['requestNumber'] . ' ' . $t['fromSection'] . ' ' . $t['toSection'] . ' ' . $t['requestedBy']) }}"
                 onclick="openDetailModal({{ json_encode($t) }})">
                
                <div class="flex flex-col md:flex-row items-start gap-4">
                    {{-- Flow Visual --}}
                    <div class="flex items-center gap-3 self-center md:self-start">
                        <div class="w-16 h-16 bg-gradient-to-br {{ $fromColor }} rounded-xl flex items-center justify-center text-2xl text-white shadow-sm">
                            @if($t['fromSection'] == 'kitchen') 👨‍🍳 @elseif($t['fromSection'] == 'cake') 🎂 @else 🥐 @endif
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            <div class="text-xs text-gray-500 font-medium uppercase">{{ $t['status'] }}</div>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br {{ $toColor }} rounded-xl flex items-center justify-center text-2xl text-white shadow-sm">
                             @if($t['toSection'] == 'kitchen') 👨‍🍳 @elseif($t['toSection'] == 'cake') 🎂 @else 🥐 @endif
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="flex-1 w-full">
                        <div class="flex flex-col md:flex-row md:items-start justify-between mb-2">
                            <div>
                                <div class="flex flex-wrap items-center gap-3 mb-1">
                                    <h3 class="text-xl font-medium text-gray-900">{{ $t['requestNumber'] }}</h3>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold border {{ $statusBadgeClass }} uppercase flex items-center gap-1">
                                        {{ $t['status'] }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $priorityClass }} uppercase">
                                        {{ $t['priority'] }}
                                    </span>
                                </div>
                                <p class="text-gray-600 flex items-center gap-1">
                                    <span class="capitalize font-medium">{{ $t['fromSection'] }}</span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    <span class="capitalize font-medium">{{ $t['toSection'] }}</span>
                                    <span class="text-gray-400">•</span>
                                    <span>{{ count($t['items']) }} item{{ count($t['items']) > 1 ? 's' : '' }}</span>
                                </p>
                            </div>
                            <div class="text-left md:text-right mt-2 md:mt-0">
                                <div class="text-2xl font-bold text-blue-600">Rs {{ number_format($t['totalValue']) }}</div>
                                <div class="text-sm text-gray-500">Total value</div>
                            </div>
                        </div>

                        {{-- Items Preview --}}
                        <div class="bg-gray-50 rounded-xl p-3 mb-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                                @foreach($t['items']->take(3) as $item)
                                    <div class="flex items-center gap-2 bg-white rounded-lg p-2 border border-gray-100">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $item['productName'] }}</div>
                                            <div class="text-xs text-gray-500">{{ $item['quantity'] }} {{ $item['unit'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                                @if(count($t['items']) > 3)
                                    <div class="flex items-center justify-center bg-gray-100 rounded-lg p-2">
                                        <span class="text-sm font-medium text-gray-600">+{{ count($t['items']) - 3 }} more</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Footer Meta --}}
                        <div class="flex flex-wrap items-center justify-between text-sm gap-2">
                            <div class="flex items-center gap-4 text-gray-600">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    <span class="font-medium">{{ $t['requestedBy'] }}</span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-gray-500">{{ $t['requestedByRole'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span>{{ $t['requestedAt'] }}</span>
                                </div>
                            </div>
                            
                            {{-- Action Buttons (Visible on card for quick access, stops propagation) --}}
                            <div class="flex gap-2" onclick="event.stopPropagation()">
                                @if($t['status'] == 'pending')
                                    <button onclick="openApproveModal({{ json_encode($t) }})" class="h-8 px-3 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                        Approve
                                    </button>
                                    <button onclick="openRejectModal({{ json_encode($t) }})" class="h-8 px-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                        Reject
                                    </button>
                                @endif
                                <button onclick="openDetailModal({{ json_encode($t) }})" class="h-8 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                    Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- 
        ================= MODALS ================= 
    --}}

    {{-- DETAIL MODAL --}}
    <div id="modal-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-5xl max-h-[95vh] overflow-y-auto p-6 shadow-2xl relative">
            <button onclick="closeModals()" class="absolute top-6 right-6 p-2 bg-gray-100 rounded-full hover:bg-gray-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="mb-6">
                <h2 class="text-2xl font-bold flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    Transfer Details
                </h2>
                <p id="detail-req-num" class="text-gray-600"></p>
            </div>

            <div class="space-y-6">
                {{-- Status Banner --}}
                <div id="detail-status-banner" class="rounded-xl p-4 border-2 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div id="detail-status-icon" class="w-8 h-8"></div>
                        <div>
                            <div id="detail-status-text" class="text-lg font-medium capitalize"></div>
                            <div id="detail-status-desc" class="text-sm text-gray-600"></div>
                        </div>
                    </div>
                    <span id="detail-priority" class="text-sm py-1 px-3 rounded-full text-white uppercase font-bold"></span>
                </div>

                {{-- Route Visual --}}
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border-2 border-blue-200">
                    <div class="flex items-center justify-center gap-4 md:gap-12">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-2 text-3xl text-white shadow-lg">🏢</div>
                            <div id="detail-from" class="text-lg font-medium capitalize"></div>
                            <div class="text-sm text-gray-600">Source</div>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <svg class="w-10 h-10 text-blue-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            <div id="detail-total-items" class="text-sm font-medium text-gray-600"></div>
                            <div id="detail-total-value" class="text-2xl font-bold text-blue-600"></div>
                        </div>
                        <div class="text-center">
                            <div class="w-20 h-20 bg-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-2 text-3xl text-white shadow-lg">🏢</div>
                            <div id="detail-to" class="text-lg font-medium capitalize"></div>
                            <div class="text-sm text-gray-600">Destination</div>
                        </div>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                        <h3 class="font-medium text-gray-900">Items List</h3>
                    </div>
                    <div class="p-5 space-y-2" id="detail-items-list">
                    </div>
                    
                    {{-- Footer Meta --}}
                    <div class="bg-gray-50 px-5 py-4 border-t-2 border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Requested By --}}
                        <div>
                            <div class="text-xs uppercase font-bold text-gray-400 mb-1">Requested By</div>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold" id="detail-req-avatar">
                                    KP
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900" id="detail-req-name">Kasun Perera</div>
                                    <div class="text-xs text-gray-500" id="detail-req-role">Bakery Supervisor</div>
                                    <div class="text-xs text-gray-400 mt-0.5" id="detail-req-date">2024-12-04 08:15 AM</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Scheduled --}}
                        <div>
                            <div class="text-xs uppercase font-bold text-gray-400 mb-1">Scheduled Date</div>
                            <div class="flex items-center gap-2 text-gray-700 font-medium">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <span id="detail-scheduled">2024-12-04</span>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <div class="text-xs uppercase font-bold text-gray-400 mb-1">Notes</div>
                            <p class="text-sm text-gray-600 leading-relaxed" id="detail-notes">
                                Needed for croissant production batch scheduled at 10 AM
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Audit Trail --}}
                <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-5 py-3 border-b-2 border-gray-200">
                        <h3 class="font-medium text-gray-900">Audit Trail</h3>
                    </div>
                    <div class="p-5 space-y-4" id="detail-audit-trail">
                        </div>
                </div>

                {{-- Action Buttons --}}
                <div id="detail-actions" class="flex gap-3 pt-4 border-t-2 border-gray-200">
                    </div>
            </div>
        </div>
    </div>

    {{-- UPDATED APPROVE MODAL --}}
<div id="modal-approve" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-3">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Approve Transfer Request
            </h2>
            <p id="approve-subtitle" class="text-base text-gray-600 mt-1"></p>
        </div>

        <div class="space-y-4">
            {{-- Items List for Approval --}}
            <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                <h3 class="font-medium text-green-900 mb-2">Transfer Items</h3>
                <div id="approve-items-list" class="space-y-2 max-h-60 overflow-y-auto pr-2">
                    </div>
            </div>

            {{-- Warning --}}
            <div class="bg-yellow-50 rounded-xl p-4 border-2 border-yellow-200">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div>
                        <h4 class="font-medium text-yellow-900 mb-1">Important</h4>
                        <p class="text-sm text-yellow-800">
                            By approving this transfer, you confirm that items are available in the 
                            <span id="approve-from-section" class="font-medium capitalize"></span> section 
                            and can be released.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button onclick="closeModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all">Cancel</button>
                <button onclick="confirmAction('Approved')" class="flex-1 h-14 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-medium shadow-lg transition-all">Approve Transfer</button>
            </div>
        </div>
    </div>
</div>

{{-- UPDATED REJECT MODAL --}}
<div id="modal-reject" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-3">
                <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Reject Transfer Request
            </h2>
            <p id="reject-subtitle" class="text-base text-gray-600 mt-1"></p>
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-base font-medium text-gray-700 mb-3">Rejection Reason *</label>
                {{-- Dynamic Reason Buttons --}}
                <div id="reject-reasons-grid" class="grid grid-cols-2 gap-2 mb-3">
                    </div>
                <textarea id="reject-note" class="w-full h-24 p-4 rounded-xl border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none resize-none" placeholder="Additional notes (optional)"></textarea>
                <input type="hidden" id="selected-reject-reason">
            </div>

            <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button onclick="closeModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all">Cancel</button>
                <button id="btn-confirm-reject" onclick="confirmAction('Rejected')" disabled class="flex-1 h-14 bg-gradient-to-br from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-xl font-medium shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed">Reject Transfer</button>
            </div>
        </div>
    </div>
</div>

{{-- COMPLETE TRANSFER MODAL --}}
<div id="modal-complete" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl w-full max-w-2xl p-6 shadow-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold flex items-center gap-3">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Complete Transfer
            </h2>
            <p id="complete-subtitle" class="text-base text-gray-600 mt-1"></p>
        </div>

        <div class="space-y-4">
            {{-- Items List for Completion --}}
            <div class="bg-emerald-50 rounded-xl p-4 border-2 border-emerald-200">
                <h3 class="font-medium text-emerald-900 mb-2">Receive Items</h3>
                <p class="text-sm text-emerald-800 mb-3">Please verify and enter the quantity physically received.</p>
                <div id="complete-items-list" class="space-y-2 max-h-60 overflow-y-auto pr-2">
                    {{-- JS populated --}}
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-4 border-t-2 border-gray-200">
                <button onclick="closeModals()" class="flex-1 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-medium transition-all">Cancel</button>
                <button onclick="confirmAction('Completed')" class="flex-1 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white rounded-xl font-medium shadow-lg transition-all">Confirm Completion</button>
            </div>
        </div>
    </div>
</div>

    {{-- CREATE PLACEHOLDER --}}
    <div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl w-full max-w-md p-8 text-center shadow-2xl">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Create New Transfer</h2>
            <p class="text-gray-600 mb-6">This feature would open a multi-step form to select items, source, and destination sections.</p>
            <button onclick="closeModals()" class="h-12 px-6 bg-gray-900 text-white rounded-xl font-medium">Close Placeholder</button>
        </div>
    </div>

</div>

<script>
    // State
    let currentTransfer = null;

    // Elements
    const searchInput = document.getElementById('search-input');
    const transferCards = document.querySelectorAll('.transfer-card');
    const viewTabs = document.querySelectorAll('.view-tab');

    // Filtering Logic
    function filterTransfers(viewMode, query) {
        transferCards.forEach(card => {
            const status = card.dataset.status;
            const searchText = card.dataset.search;
            
            // View Mode Logic
            let matchesView = false;
            if (viewMode === 'all') matchesView = true;
            else if (viewMode === 'history') matchesView = ['completed', 'rejected', 'cancelled'].includes(status);
            else matchesView = status === viewMode;

            // Search Logic
            const matchesSearch = searchText.includes(query);

            if (matchesView && matchesSearch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Tab Listeners
    viewTabs.forEach(tab => {
        tab.addEventListener('click', () => {
            viewTabs.forEach(t => {
                t.classList.remove('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600', 'text-white', 'shadow-lg', 'active');
                t.classList.add('bg-white', 'text-gray-700', 'border-2', 'border-gray-200');
            });
            tab.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-gray-200');
            tab.classList.add('bg-gradient-to-br', 'from-blue-500', 'to-indigo-600', 'text-white', 'shadow-lg', 'active');
            
            filterTransfers(tab.dataset.view, searchInput.value.toLowerCase());
        });
    });

    // Search Listener
    searchInput.addEventListener('input', (e) => {
        const activeTab = document.querySelector('.view-tab.active').dataset.view;
        filterTransfers(activeTab, e.target.value.toLowerCase());
    });

    // Modal Functions
    function closeModals() {
        document.querySelectorAll('[id^="modal-"]').forEach(m => m.classList.add('hidden'));
        currentTransfer = null;
    }

    window.openCreateModal = function() {
        document.getElementById('modal-create').classList.remove('hidden');
    }

    // --- APPROVE MODAL LOGIC ---
window.openApproveModal = function(transfer) {
    currentTransfer = transfer; // Store globally for action

    // 1. Populate Header Info
    document.getElementById('approve-subtitle').textContent = 
        `${transfer.requestNumber} • ${transfer.fromSection} → ${transfer.toSection}`;
    
    document.getElementById('approve-from-section').textContent = transfer.fromSection;

    // 2. Populate Items List
    const listContainer = document.getElementById('approve-items-list');
    listContainer.innerHTML = ''; // Clear previous items

    transfer.items.forEach(item => {
        const itemHtml = `
            <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                <div>
                    <div class="font-medium text-gray-900">${item.productName}</div>
                    <div class="text-sm text-gray-600">${item.category}</div>
                </div>
                <div class="text-right flex items-center gap-3">
                    <div class="text-xs text-gray-500">Req: ${item.quantity}</div>
                    <div class="flex items-center gap-1">
                         <input type="number" 
                                id="approve-qty-${item.id}" 
                                class="w-20 p-1 border rounded text-right font-medium text-gray-900 focus:ring-2 focus:ring-green-500 outline-none" 
                                value="${item.quantity}" 
                                min="0" 
                                max="${item.quantity}"
                                onchange="if(this.value > ${item.quantity}) this.value = ${item.quantity}; if(this.value < 0) this.value = 0;">
                         <span class="text-sm text-gray-500">${item.unit}</span>
                    </div>
                </div>
            </div>
        `;
        listContainer.insertAdjacentHTML('beforeend', itemHtml);
    });

    document.getElementById('modal-approve').classList.remove('hidden');
}

// --- REJECT MODAL LOGIC ---
window.openRejectModal = function(transfer) {
    currentTransfer = transfer;

    // 1. Reset Form
    document.getElementById('reject-subtitle').textContent = 
        `${transfer.requestNumber} • ${transfer.fromSection} → ${transfer.toSection}`;
    document.getElementById('selected-reject-reason').value = '';
    document.getElementById('reject-note').value = '';
    document.getElementById('btn-confirm-reject').disabled = true;

    // 2. Generate Reason Buttons
    const reasons = [
        'Insufficient stock available',
        'Items expired or near expiry',
        'Quality concerns',
        'Incorrect section routing',
        'Duplicate request',
        'Other'
    ];

    const grid = document.getElementById('reject-reasons-grid');
    grid.innerHTML = ''; // Clear previous

    reasons.forEach(reason => {
        const btn = document.createElement('button');
        btn.textContent = reason;
        btn.className = 'p-3 rounded-xl border-2 transition-all text-sm border-gray-200 bg-white text-gray-700 hover:bg-gray-50 text-left';
        
        btn.onclick = () => {
            // Visual selection
            Array.from(grid.children).forEach(child => {
                child.className = 'p-3 rounded-xl border-2 transition-all text-sm border-gray-200 bg-white text-gray-700 hover:bg-gray-50 text-left';
            });
            btn.className = 'p-3 rounded-xl border-2 transition-all text-sm border-red-500 bg-red-50 text-red-900 text-left font-medium';
            
            // Set value & Enable button
            document.getElementById('selected-reject-reason').value = reason;
            document.getElementById('btn-confirm-reject').disabled = false;
        };
        grid.appendChild(btn);
    });

    document.getElementById('modal-reject').classList.remove('hidden');
}

// --- COMPLETE MODAL LOGIC ---
window.openCompleteModal = function(transfer) {
    currentTransfer = transfer;
    
    document.getElementById('complete-subtitle').textContent = 
        `${transfer.requestNumber} • ${transfer.fromSection} → ${transfer.toSection}`;

    const listContainer = document.getElementById('complete-items-list');
    listContainer.innerHTML = '';

    transfer.items.forEach(item => {
        // Default received to approved quantity (or dispatched if available)
        // If approvedQuantity is available, use it, else quantity
        const safeQty = item.approvedQuantity !== null ? item.approvedQuantity : item.quantity;
        
        const itemHtml = `
            <div class="flex items-center justify-between bg-white rounded-lg p-3 shadow-sm border border-gray-100">
                <div>
                    <div class="font-medium text-gray-900">${item.productName}</div>
                    <div class="text-sm text-gray-600">${item.category}</div>
                </div>
                <div class="text-right flex items-center gap-3">
                    <div class="text-xs text-gray-500">Exp: ${safeQty}</div>
                    <div class="flex items-center gap-1">
                         <input type="number" 
                                id="receive-qty-${item.id}" 
                                class="w-20 p-1 border rounded text-right font-medium text-gray-900 focus:ring-2 focus:ring-emerald-500 outline-none" 
                                value="${safeQty}" 
                                min="0" 
                                max="${safeQty}"
                                onchange="if(this.value > ${safeQty}) this.value = ${safeQty}; if(this.value < 0) this.value = 0;">
                         <span class="text-sm text-gray-500">${item.unit}</span>
                    </div>
                </div>
            </div>
        `;
        listContainer.insertAdjacentHTML('beforeend', itemHtml);
    });

    document.getElementById('modal-complete').classList.remove('hidden');
}

    // --- ACTION SUBMISSION ---
    window.confirmAction = function(action) {
        if(!currentTransfer) return;

        const reason = document.getElementById('selected-reject-reason')?.value || 
                       document.getElementById('reject-note')?.value || '';

        // UI Loading State (Optional optimization: change button text)
        const btn = document.activeElement;
        if(btn && btn.tagName === 'BUTTON') {
             btn.disabled = true;
             btn.textContent = 'Processing...';
        }

        fetch('{{ route("inventory.transfer.update-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                id: currentTransfer.id,
                action: action,
                rejection_reason: action === 'Rejected' ? reason : null,
                items: action === 'Approved' ? currentTransfer.items.map(i => ({
                    id: i.id, 
                    approved_qty: document.getElementById(`approve-qty-${i.id}`) ? document.getElementById(`approve-qty-${i.id}`).value : i.quantity
                })) : (action === 'Completed' ? currentTransfer.items.map(i => ({
                    id: i.id,
                    received_qty: document.getElementById(`receive-qty-${i.id}`) ? document.getElementById(`receive-qty-${i.id}`).value : (i.approvedQuantity || i.quantity)
                })) : [])
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Success
                let icon = 'success';
                let title = 'Success';
                
                if(action === 'Rejected') { icon = 'warning'; title = 'Rejected'; }
                
                Swal.fire({
                    title: title,
                    text: data.message,
                    icon: icon,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4F46E5' // Indigo-600
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });

            } else {
                throw new Error(data.message || 'Action failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'OK'
            });
            if(btn) {
                btn.disabled = false;
                btn.textContent = action; // Reset button text roughly
            }
        });
    }

    window.openDetailModal = function(transfer) {
        currentTransfer = transfer;
        
        // Populate Header
        document.getElementById('detail-req-num').textContent = transfer.requestNumber;
        document.getElementById('detail-from').textContent = transfer.fromSection;
        document.getElementById('detail-to').textContent = transfer.toSection;
        document.getElementById('detail-total-items').textContent = transfer.items.length + ' Items';
        document.getElementById('detail-total-value').textContent = 'Rs ' + transfer.totalValue.toLocaleString();

        // Populate Status Banner
        const banner = document.getElementById('detail-status-banner');
        const icon = document.getElementById('detail-status-icon');
        const text = document.getElementById('detail-status-text');
        const desc = document.getElementById('detail-status-desc');
        const priority = document.getElementById('detail-priority');

        // Logic for styling based on status (Simplified)
        let colors = 'bg-gray-50 border-gray-300';
        let iconHtml = '';
        
        if(transfer.status === 'pending') {
            colors = 'bg-yellow-50 border-yellow-300';
            iconHtml = '<svg class="w-full h-full text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            desc.textContent = 'Awaiting approval';
        } else if (transfer.status === 'approved') {
            colors = 'bg-green-50 border-green-300';
            iconHtml = '<svg class="w-full h-full text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
            desc.textContent = `Approved by ${transfer.approvedBy || 'N/A'}`;
        }
        // ... add other statuses as needed

        banner.className = `rounded-xl p-4 border-2 flex items-center justify-between ${colors}`;
        icon.innerHTML = iconHtml;
        text.textContent = transfer.status;
        
        priority.textContent = transfer.priority + ' Priority';
        priority.className = `text-sm py-1 px-3 rounded-full text-white uppercase font-bold ${
            transfer.priority === 'urgent' ? 'bg-red-500' : 
            transfer.priority === 'high' ? 'bg-orange-500' : 'bg-gray-500'
        }`;

        // Populate Items
        const itemsList = document.getElementById('detail-items-list');
        itemsList.innerHTML = '';
        transfer.items.forEach(item => {
            // Determine Base Unit Symbol
            let baseUnit = '';
            const uType = item.unitType; 
            if(uType == 1 || uType == 3) baseUnit = 'g';
            else if(uType == 2 || uType == 4) baseUnit = 'ml';
            else if(uType == 5) baseUnit = 'pcs';

            let qtyInUnitDisplay = item.qtyInUnit > 0 ? `<div class="text-xs text-gray-500 font-medium">(${Number(item.qtyInUnit).toLocaleString()} ${baseUnit})</div>` : '';

            // Partial Approval Logic
            let quantityDisplay = `<div class="text-lg font-bold text-gray-900">${item.quantity} ${item.unit}</div>`;
            
            if (item.status === 'Approved' && item.approvedQuantity !== null && item.approvedQuantity < item.quantity) {
                quantityDisplay = `
                    <div class="flex flex-col items-end">
                        <div class="text-sm text-gray-400 line-through mb-0.5">${item.quantity} ${item.unit}</div>
                        <div class="text-lg font-bold text-red-600">${item.approvedQuantity} ${item.unit}</div>
                    </div>
                `;
            }

            itemsList.innerHTML += `
                <div class="flex items-center justify-between bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${item.productName}</div>
                            <div class="text-sm text-gray-600">${item.category}</div>
                            ${item.expiry !== 'N/A' ? `<div class="text-xs text-red-500 mt-1 font-medium">Expires: ${item.expiry}</div>` : ''}
                        </div>
                    </div>
                    <div class="text-right">
                        ${quantityDisplay}
                        ${qtyInUnitDisplay}
                        ${item.unitPrice > 0 ? `<div class="text-sm text-gray-600">Rs ${Number(item.unitPrice).toLocaleString()}</div>` : ''}
                        <div class="text-xs text-gray-500 font-mono">Batch: ${item.batchNumber}</div>
                    </div>
                </div>
            `;
        });
        
        // Populate Footer Meta
        const initials = transfer.requestedBy.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        document.getElementById('detail-req-avatar').textContent = initials;
        document.getElementById('detail-req-name').textContent = transfer.requestedBy;
        document.getElementById('detail-req-role').textContent = transfer.requestedByRole;
        document.getElementById('detail-req-date').textContent = transfer.requestedAt;
        document.getElementById('detail-scheduled').textContent = transfer.scheduledDate;
        document.getElementById('detail-notes').textContent = transfer.notes || 'No additional notes provided.';

        // Populate Audit Trail
        const auditList = document.getElementById('detail-audit-trail');
        auditList.innerHTML = '';
        transfer.auditTrail.forEach((entry, idx) => {
            auditList.innerHTML += `
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        ${idx < transfer.auditTrail.length - 1 ? '<div class="w-0.5 h-full bg-gray-200 mt-1"></div>' : ''}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between mb-1">
                            <div class="font-medium text-gray-900">${entry.action}</div>
                            <div class="text-xs text-gray-500">${entry.timestamp}</div>
                        </div>
                        <div class="text-sm text-gray-600 mb-1">${entry.details}</div>
                        <div class="text-xs text-gray-500">${entry.performedBy} • ${entry.role}</div>
                    </div>
                </div>
            `;
        });

        // Populate Actions
        const actionsDiv = document.getElementById('detail-actions');
        actionsDiv.innerHTML = '';
        if (transfer.status === 'pending') {
            actionsDiv.innerHTML = `
                <button onclick="openRejectModal(currentTransfer)" class="flex-1 h-12 bg-red-100 text-red-700 rounded-xl font-medium">Reject</button>
                <button onclick="openApproveModal(currentTransfer)" class="flex-1 h-12 bg-green-600 text-white rounded-xl font-medium shadow-lg">Approve</button>
            `;
        } else if (transfer.status === 'approved') {
            actionsDiv.innerHTML = `
                <button onclick="startTransfer(${transfer.id})" class="w-full h-12 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    Start Transfer
                </button>
            `;
        } else if (transfer.status === 'in-transit') {
            actionsDiv.innerHTML = `<button onclick="openCompleteModal(currentTransfer)" class="flex-1 h-12 bg-emerald-600 text-white rounded-xl font-medium shadow-lg">Complete Transfer</button>`;
        } else {
            actionsDiv.innerHTML = `<button onclick="closeModals()" class="flex-1 h-12 bg-gray-100 text-gray-700 rounded-xl font-medium">Close</button>`;
        }

        document.getElementById('modal-detail').classList.remove('hidden');
    }

    // Start Transfer Function
    window.startTransfer = function(id) {
        Swal.fire({
            title: 'Start Transfer?',
            text: "This will mark items as 'In-Transit' and initiate the transfer process to the destination.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4F46E5',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Start Transfer'
        }).then((result) => {
            if (result.isConfirmed) {
                // Determine logic
                currentTransfer = { id: id }; // temp context
                confirmAction('Started');
            }
        });
    }

</script>
@endsection