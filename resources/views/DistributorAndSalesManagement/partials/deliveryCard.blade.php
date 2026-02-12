<div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all delivery-card" data-delivery="{{ json_encode($delivery) }}">
    <div class="flex items-start justify-between mb-3">
        <div>
            <div class="text-sm text-purple-600 mb-1 font-bold">
                {{ $delivery->deliveryType === 'customer' ? $delivery->invoiceNumber : ($delivery->deliveryType === 'outlet-transfer' ? 'Transfer' : 'Ad-hoc') }}
            </div>
            <div class="text-gray-900 font-medium text-sm">
                {{ $delivery->deliveryType === 'customer' ? $delivery->customerName : ($delivery->deliveryType === 'outlet-transfer' ? $delivery->outletName : 'Special Delivery') }}
            </div>
        </div>
        <span class="text-lg">
             @if($delivery->priority == 'urgent') 🔴 @elseif($delivery->priority == 'low') 🔵 @else ⚪ @endif
        </span>
    </div>

    <div class="text-sm text-gray-600 mb-3 flex items-start gap-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
        <span class="line-clamp-2">{{ $delivery->deliveryAddress }}</span>
    </div>

    <div class="flex items-center gap-2 mb-3">
        @php
            $statusColors = [
                'pending' => 'bg-gray-100 text-gray-700 border-gray-300',
                'scheduled' => 'bg-blue-100 text-blue-700 border-blue-300',
                'in-transit' => 'bg-purple-100 text-purple-700 border-purple-300',
                'delivered' => 'bg-green-100 text-green-700 border-green-300',
            ];
            $color = $statusColors[$delivery->status] ?? 'bg-gray-100';
        @endphp
        <span class="{{ $color }} text-[10px] px-2 py-0.5 border rounded uppercase font-bold">
            {{ $delivery->status }}
        </span>
        @if($delivery->scheduledDate)
            <span class="text-xs text-gray-500">
                {{ \Carbon\Carbon::parse($delivery->scheduledDate)->format('M d') }}
            </span>
        @endif
    </div>

    <div class="flex gap-2">
        @if($delivery->status === 'pending')
            <button onclick="openScheduleModal(this.closest('.delivery-card'))" class="flex-1 h-8 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-xs transition-colors font-bold">Schedule</button>
        @endif
        <button onclick="openViewDeliveryDetailsModal(this.closest('.delivery-card'))" class="flex-1 h-8 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs transition-colors font-bold">Details</button>
    </div>
</div>