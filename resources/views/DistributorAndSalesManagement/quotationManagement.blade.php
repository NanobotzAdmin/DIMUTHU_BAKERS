@extends('layouts.app')
@section('title', 'Quotation Management')

{{--
=================================================================================
DUMMY DATA & LOGIC (Simulating Controller & Store)
=================================================================================
--}}
@php
    use App\CommonVariables;

    if (!function_exists('getStatusConfig')) {
        function getStatusConfig($status)
        {
            return match ($status) {
                CommonVariables::$quotationStatusDraft => ['color' => 'bg-gray-100 text-gray-700 border-gray-300', 'label' => 'Draft', 'icon' => 'edit'],
                CommonVariables::$quotationStatusPendingApproval => ['color' => 'bg-yellow-100 text-yellow-700 border-yellow-300', 'label' => 'Pending Approval', 'icon' => 'clock'],
                CommonVariables::$quotationStatusApproved => ['color' => 'bg-blue-100 text-blue-700 border-blue-300', 'label' => 'Approved', 'icon' => 'check-circle'],
                CommonVariables::$quotationStatusSent => ['color' => 'bg-indigo-100 text-indigo-700 border-indigo-300', 'label' => 'Sent', 'icon' => 'send'],
                CommonVariables::$quotationStatusCustomerAccepted => ['color' => 'bg-green-100 text-green-700 border-green-300', 'label' => 'Accepted', 'icon' => 'check-circle'],
                CommonVariables::$quotationStatusCustomerRejected => ['color' => 'bg-red-100 text-red-700 border-red-300', 'label' => 'Rejected', 'icon' => 'x-circle'],
                CommonVariables::$quotationStatusExpired => ['color' => 'bg-orange-100 text-orange-700 border-orange-300', 'label' => 'Expired', 'icon' => 'alert-triangle'],
                CommonVariables::$quotationStatusConverted => ['color' => 'bg-emerald-100 text-emerald-700 border-emerald-300', 'label' => 'Converted', 'icon' => 'file-check'],
                CommonVariables::$quotationStatusCancelled => ['color' => 'bg-slate-100 text-slate-700 border-slate-300', 'label' => 'Cancelled', 'icon' => 'ban'],
                default => ['color' => 'bg-gray-100 text-gray-700 border-gray-300', 'label' => 'Draft', 'icon' => 'edit'],
            };
        }
    }
@endphp


@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-4 md:p-6">

        {{-- ================= HEADER ================= --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl text-gray-900 font-bold">Quotation Management</h1>
                        <p class="text-gray-600">Create, send, and track customer quotations</p>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="flex gap-3">
                    <button onclick="openSettingsModal()"
                        class="h-12 px-5 bg-white hover:bg-gray-50 text-gray-700 border-2 border-gray-200 rounded-xl flex items-center gap-2 transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path
                                d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                            </path>
                        </svg>
                        Settings
                    </button>
                    <button onclick="toggleModal('createQuotationModal')"
                        class="h-12 px-6 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl flex items-center gap-2 shadow-lg transition-all duration-300">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        New Quotation
                    </button>
                </div>
            </div>

            {{-- ================= SUMMARY CARDS ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- Total --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <span class="text-2xl font-semibold">{{ $summary['totalQuotations'] }}</span>
                    </div>
                    <h3 class="text-gray-600 mb-1">Total Quotations</h3>
                    <p class="text-gray-900 font-medium">Rs {{ number_format($summary['totalValue'], 2) }}</p>
                </div>

                {{-- Sent --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </div>
                        <span class="text-2xl font-semibold">{{ $summary['sentCount'] }}</span>
                    </div>
                    <h3 class="text-gray-600 mb-1">Sent & Pending</h3>
                    <p class="text-gray-900 font-medium">Rs {{ number_format($summary['pendingValue'], 2) }}</p>
                </div>

                {{-- Accepted --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <span class="text-2xl font-semibold">{{ $summary['acceptedCount'] }}</span>
                    </div>
                    <h3 class="text-gray-600 mb-1">Accepted</h3>
                    <p class="text-gray-900 font-medium">Rs {{ number_format($summary['acceptedValue'], 2) }}</p>
                </div>

                {{-- Conversion --}}
                <div
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                <polyline points="17 6 23 6 23 12"></polyline>
                            </svg>
                        </div>
                        <span class="text-2xl font-semibold">{{ $summary['conversionRate'] }}%</span>
                    </div>
                    <h3 class="text-gray-600 mb-1">Conversion Rate</h3>
                    <p class="text-gray-900 font-medium">{{ $summary['expiringThisWeek'] }} expiring this week</p>
                </div>
            </div>

            {{-- ================= TABS ================= --}}
            <div
                class="bg-white rounded-2xl p-2 shadow-sm border border-gray-100 flex gap-2 overflow-x-auto scrollbar-hide">
                @php
                    $tabs = [
                        ['mode' => 'all', 'label' => 'All', 'icon' => 'file-text'],
                        ['mode' => CommonVariables::$quotationStatusDraft, 'label' => 'Draft', 'icon' => 'edit'],
                        ['mode' => CommonVariables::$quotationStatusPendingApproval, 'label' => 'Pending Approval', 'icon' => 'clock'],
                        ['mode' => CommonVariables::$quotationStatusSent, 'label' => 'Sent', 'icon' => 'send'],
                        ['mode' => CommonVariables::$quotationStatusCustomerAccepted, 'label' => 'Accepted', 'icon' => 'check-circle'],
                        ['mode' => CommonVariables::$quotationStatusCustomerRejected, 'label' => 'Rejected', 'icon' => 'x-circle'],
                        ['mode' => CommonVariables::$quotationStatusExpired, 'label' => 'Expired', 'icon' => 'alert-triangle'],
                        ['mode' => CommonVariables::$quotationStatusConverted, 'label' => 'Converted', 'icon' => 'file-check'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    <a href="{{ route('quotations.index', array_merge(request()->query(), ['view' => $tab['mode']])) }}"
                        class="flex-shrink-0 flex items-center gap-2 px-4 py-2 rounded-xl transition-all duration-300 {{ $viewMode === $tab['mode'] ? 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-100' }}">
                        <span>{{ $tab['label'] }}</span>
                        <span
                            class="px-2 py-0.5 rounded-full text-xs {{ $viewMode === $tab['mode'] ? 'bg-white/20' : 'bg-gray-200' }}">
                            {{ $tab['mode'] === 'all' ? $summary['totalQuotations'] : ($summary['counts'][$tab['mode']] ?? 0) }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ================= SEARCH & FILTER ================= --}}
        <form method="GET" action="{{ route('quotations.index') }}" class="mb-6 flex flex-col md:flex-row gap-4">
            <input type="hidden" name="view" value="{{ $viewMode }}">

            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by quotation number, customer name, email..."
                    class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
            </div>

            <div class="flex gap-3">
                <select name="sort" onchange="this.form.submit()"
                    class="h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors cursor-pointer">
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Sort by Date</option>
                    <option value="value" {{ request('sort') == 'value' ? 'selected' : '' }}>Sort by Value</option>
                    <option value="customer" {{ request('sort') == 'customer' ? 'selected' : '' }}>Sort by Customer</option>
                </select>
            </div>
        </form>

        {{-- ================= LIST ================= --}}
        <div class="space-y-4">
            @forelse($quotations as $quotation)
                @php
                    $statusConfig = getStatusConfig($quotation->status);
                    $daysUntilExpiry = $quotation->valid_until ? ceil(now()->diffInDays(\Carbon\Carbon::parse($quotation->valid_until), false)) : 0;
                    $isExpiringSoon = $daysUntilExpiry <= 7 && $daysUntilExpiry > 0 && ($quotation->status === CommonVariables::$quotationStatusSent || $quotation->status === CommonVariables::$quotationStatusDraft);
                    $customerName = $quotation->customer->name ?? 'Unknown Customer';
                    $customerPhone = $quotation->customer->phone ?? '-';
                    $lineItemCount = $quotation->products->count();
                    $validUntilDate = $quotation->valid_until ? \Carbon\Carbon::parse($quotation->valid_until) : null;
                @endphp

                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        {{-- Left Info --}}
                        <div class="flex-1">
                            <div class="flex items-start gap-4">
                                {{-- Icon Box --}}
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                        <polyline points="14 2 14 8 20 8" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    {{-- Title Row --}}
                                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                                        <h3 class="text-xl text-gray-900 font-medium">{{ $quotation->quotation_number }}</h3>

                                        {{-- Status Badge --}}
                                        <span
                                            class="{{ $statusConfig['color'] }} border px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs font-semibold">
                                            @if($statusConfig['icon'] == 'clock') <svg class="w-3.5 h-3.5" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                            @elseif($statusConfig['icon'] == 'check-circle') <svg class="w-3.5 h-3.5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg>
                                            @elseif($statusConfig['icon'] == 'send') <svg class="w-3.5 h-3.5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                                </svg>
                                            @elseif($statusConfig['icon'] == 'alert-triangle') <svg class="w-3.5 h-3.5"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                                    </path>
                                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                </svg>
                                            @else <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            @endif
                                            {{ $statusConfig['label'] }}
                                        </span>

                                        {{-- Versioning not implemented yet, so removed or kept static --}}

                                        @if($isExpiringSoon)
                                            <span
                                                class="bg-orange-100 text-orange-700 border border-orange-300 px-2 py-1 rounded-lg flex items-center gap-1.5 text-xs font-semibold">
                                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path
                                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z">
                                                    </path>
                                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                </svg>
                                                Expires in {{ $daysUntilExpiry }}d
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Details --}}
                                    <div class="flex flex-wrap items-center gap-4 mb-3 text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            <span>{{ $customerName }}</span>
                                        </div>
                                        @if($customerPhone)
                                            <div class="flex items-center gap-2"><span
                                                    class="text-gray-400">•</span><span>{{ $customerPhone }}</span></div>
                                        @endif
                                    </div>

                                    {{-- Line Items --}}
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span>{{ $lineItemCount }} item{{ $lineItemCount !== 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Actions --}}
                        <div class="flex flex-col items-end gap-3 min-w-[200px]">
                            <div class="text-right">
                                <div class="text-2xl text-gray-900 mb-1">Rs {{ number_format($quotation->grand_total, 2) }}
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-500 justify-end">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    <span>Valid until {{ $validUntilDate ? $validUntilDate->format('M d, Y') : 'N/A' }}</span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                {{-- View Button --}}
                                <button onclick="openDetailModal({{ json_encode($quotation) }})"
                                    class="h-10 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl flex items-center gap-2 transition-all duration-300">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View
                                </button>

                                {{-- Send Email (Draft/Approved) --}}
                                @if(($quotation->status === CommonVariables::$quotationStatusDraft || $quotation->status === CommonVariables::$quotationStatusApproved) && ($quotation->customer && $quotation->customer->email))
                                    <button onclick="confirmAction('Send Email?', 'send-email', {{ $quotation->id }})"
                                        class="h-10 px-4 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl flex items-center gap-2 transition-all duration-300">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                            </path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </button>
                                @endif

                                {{-- Approve (Pending) --}}
                                @if($quotation->status === 'pending-approval')
                                    <button onclick="confirmAction('Approve Quotation?', 'approve', {{ $quotation->id }})"
                                        class="h-10 px-4 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl flex items-center gap-2 transition-all duration-300">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </button>
                                @endif

                                {{-- Convert (Accepted) --}}
                                @if($quotation->status === CommonVariables::$quotationStatusCustomerAccepted)
                                    <button onclick="confirmAction('Convert to Order?', 'convert', {{ $quotation->id }})"
                                        class="h-10 px-4 bg-gradient-to-r from-emerald-600 to-green-600 hover:from-emerald-700 hover:to-green-700 text-white rounded-xl flex items-center gap-2 transition-all duration-300">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                        </svg>
                                        Convert
                                    </button>
                                @endif

                                {{-- Test Accept/Reject (Sent) --}}
                                @if($quotation->status === 'sent')
                                    <button onclick="confirmAction('Mark as Accepted?', 'accept', {{ $quotation->id }})"
                                        class="h-10 px-4 bg-green-100 hover:bg-green-200 text-green-700 rounded-xl flex items-center gap-2 transition-all duration-300">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                    </button>
                                    <button onclick="confirmAction('Mark as Rejected?', 'reject', {{ $quotation->id }})"
                                        class="h-10 px-4 bg-red-100 hover:bg-red-200 text-red-700 rounded-xl flex items-center gap-2 transition-all duration-300">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>
                    <h3 class="text-xl text-gray-600 mb-2">No quotations found</h3>
                    <p class="text-gray-500 mb-6">
                        {{ request('search') ? 'Try adjusting your search query' : 'Get started by creating your first quotation' }}
                    </p>
                    @if(!request('search'))
                        <button onclick="toggleModal('createQuotationModal')"
                            class="inline-flex h-12 px-6 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl items-center gap-2 shadow-lg transition-all duration-300">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Create Quotation
                        </button>
                    @endif
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $quotations->withQueryString()->links() }}
        </div>
    </div>

    {{-- ================= MODALS ================= --}}






    @include('DistributorAndSalesManagement.modals.viewQuotation')
    @include('DistributorAndSalesManagement.modals.createQuotation')
    @include('DistributorAndSalesManagement.modals.settingsQuotation')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Modal Toggle Logic
            function toggleModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            }



            // SweetAlert2 Confirmation Wrapper
            function confirmAction(title, actionType, id) {
                Swal.fire({
                    title: title,
                    text: "Are you sure you want to proceed?",
                    icon: actionType === 'reject' || actionType === 'delete' ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonColor: actionType === 'reject' ? '#ef4444' : '#7c3aed',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Here you would typically submit a form or call an API
                        // For demo purposes, we show success
                        Swal.fire('Success!', 'Action completed successfully.', 'success').then(() => {
                            // location.reload(); // Uncomment to simulate refresh
                        });
                    }
                });
            }
        </script>
    @endpush

@endsection