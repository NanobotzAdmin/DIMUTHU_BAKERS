@extends('layouts.app')

@section('title', 'Payment Approval')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <a href="{{ route('order-management.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 flex items-center gap-2">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
        <div class="mt-2 md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Payment Approval
                </h2>
                <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="bi bi-person-circle mr-1.5 h-5 w-5 text-gray-400"></i>
                        {{ $payment->orderRequest->agent->name ?? 'Unknown Agent' }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <i class="bi bi-calendar3 mr-1.5 h-5 w-5 text-gray-400"></i>
                        Requested on {{ \Carbon\Carbon::parse($payment->created_at)->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
        <div class="px-4 py-5 sm:px-6 bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Payment Verification
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Please verify the payment details before approving.
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                <i class="bi bi-clock-history mr-1.5"></i> Pending Approval
            </span>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <!-- Payment Amount -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Payment Amount</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="text-2xl font-bold text-gray-900">Rs. {{ number_format($payment->payment_amount, 2) }}</span>
                    </dd>
                </div>

                <!-- Payment Method -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex items-center gap-2">
                            @php
                                $methodIcon = match($payment->payment_method) {
                                    'Cash' => 'bi-cash-stack text-green-600',
                                    'Bank Transfer' => 'bi-bank text-blue-600',
                                    'Check' => 'bi-file-earmark-text text-purple-600',
                                    default => 'bi-credit-card text-gray-600'
                                };
                            @endphp
                            <i class="bi {{ $methodIcon }} text-lg"></i>
                            <span class="font-medium">{{ $payment->payment_method }}</span>
                        </div>
                    </dd>
                </div>

                <!-- Related Order -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-blue-50/30">
                    <dt class="text-sm font-medium text-gray-500">Order Reference</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex flex-col">
                            <span class="font-bold text-indigo-700">Order #{{ $payment->orderRequest->order_number }}</span>
                            <span class="text-xs text-gray-500 mt-0.5">Order Total: Rs. {{ number_format($payment->orderRequest->grand_total, 2) }}</span>
                            <span class="text-xs text-gray-500">Already Paid: Rs. {{ number_format($payment->orderRequest->paid_amount, 2) }}</span>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                @php
                                    $percentage = ($payment->orderRequest->grand_total > 0) ? ($payment->orderRequest->paid_amount / $payment->orderRequest->grand_total) * 100 : 0;
                                    $newPercentage = ($payment->orderRequest->grand_total > 0) ? (($payment->orderRequest->paid_amount + $payment->payment_amount) / $payment->orderRequest->grand_total) * 100 : 0;
                                @endphp
                                <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ min(100, $percentage) }}%"></div>
                                <div class="bg-indigo-300 h-1.5 rounded-full -mt-1.5 opacity-50" style="width: {{ min(100, $newPercentage) }}%"></div>
                            </div>
                            <span class="text-[10px] text-indigo-600 mt-1 font-medium italic">* Includes this pending payment</span>
                        </div>
                    </dd>
                </div>

                <!-- Notes / Reference -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Agent Note / Reference</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="p-3 bg-gray-50 rounded-md border border-gray-100 italic text-gray-600">
                            {{ $payment->notes ?: 'No additional notes provided by the agent.' }}
                        </div>
                    </dd>
                </div>

                <!-- Agent Status -->
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Agent Financial Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex gap-4">
                            <div class="flex-1 p-3 rounded-lg bg-red-50 border border-red-100">
                                <span class="block text-[10px] uppercase font-bold text-red-500">Outstanding</span>
                                <span class="text-lg font-bold text-red-700">Rs. {{ number_format($payment->orderRequest->agent->outstanding_balance, 2) }}</span>
                            </div>
                            <div class="flex-1 p-3 rounded-lg bg-green-50 border border-green-100">
                                <span class="block text-[10px] uppercase font-bold text-green-500">After Approval</span>
                                <span class="text-lg font-bold text-green-700">Rs. {{ number_format($payment->orderRequest->agent->outstanding_balance - $payment->payment_amount, 2) }}</span>
                            </div>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Action Buttons -->
        <div class="px-4 py-5 sm:px-6 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-lg">
            <button type="button" onclick="confirmRejection({{ $payment->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="bi bi-x-circle mr-2"></i> Reject Payment
            </button>
            <button type="button" onclick="approvePayment({{ $payment->id }})"
                    class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="bi bi-check2-circle mr-2"></i> Approve & Process
            </button>
        </div>
    </div>
</div>

<script>
    function approvePayment(paymentId) {
        Swal.fire({
            title: 'Confirm Approval?',
            text: "This will update the agent's balance and mark the payment as processed.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Yes, Approve',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: "{{ route('orderManagement.approvePayment') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_id: paymentId
                    }
                }).catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error.responseJSON?.message || error.statusText}`);
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Success!',
                    text: 'The payment has been approved and accounted for.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('order-management.index') }}";
                });
            }
        });
    }

    function confirmRejection(paymentId) {
        Swal.fire({
            title: 'Reject Payment?',
            text: "Please provide a reason for rejection:",
            input: 'textarea',
            inputPlaceholder: 'e.g., Bank reference not found, incorrect amount...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Reject Payment',
            inputAttributes: {
                'aria-label': 'Rejection reason'
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'You need to write a reason!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Implementation for rejection would go here (optional follow-up)
                Swal.fire('Rejected', 'The payment request has been rejected.', 'info');
            }
        });
    }
</script>
@endsection
