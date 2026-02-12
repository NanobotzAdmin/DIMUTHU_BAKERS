@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50/50 p-6 md:p-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">GRN Management</h1>
                <p class="text-gray-500 mt-1">View and manage received inventory notes</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('purchaseOrderManage.index') }}"
                    class="h-12 px-5 bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl flex items-center gap-2 font-medium shadow-md transition-all">
                    <i class="bi bi-calendar"></i>
                    Purchase Orders
                </a>
            </div>
        </div>

        <!-- Search/Filter -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('grnManagement.index') }}" method="GET" class="relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by GRN Number, Supplier, or Invoice..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border-transparent focus:bg-white focus:border-gray-200 focus:ring-2 focus:ring-gray-100 rounded-xl transition-all outline-none text-gray-900 placeholder-gray-400">
            </form>
        </div>

        <!-- GRN List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($grns as $grn)
                    <div onclick="openGrnModal('{{ $grn->id }}')"
                        class="group bg-white rounded-2xl p-5 shadow-sm border-2 border-gray-100 hover:shadow-md transition-all cursor-pointer group">
                        <div
                            class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-purple-50 to-transparent rounded-bl-full -mr-4 -mt-4 opacity-50 group-hover:scale-110 transition-transform">
                        </div>

                        <div class="flex items-center justify-between mb-4 relative">
                            <div>
                                <h3 class="text-xl font-medium text-gray-900 group-hover:text-pink-600 transition-colors">
                                    {{ $grn->grn_number }}
                                </h3>
                                <div class="text-xs text-gray-500 mt-1">{{ $grn->created_at->format('M d, Y') }}</div>
                            </div>
                            <div class="h-10 w-10 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center">
                                <i class="bi bi-hexagon text-blue-600 text-xl"></i>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4 relative">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-person-workspace text-gray-600 mr-2"></i>
                                <span class="truncate">{{ $grn->supplier->name ?? 'Unknown Supplier' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="bi bi-cart text-gray-600 mr-2"></i>
                                <span>PO: {{ $grn->purchaseOrder->po_number ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-50 flex items-center justify-between relative">
                            <span class="text-sm font-medium text-blue-500">{{ $grn->stockIns->count() }} Items</span>
                            <span class="text-lg font-bold text-gray-900">Rs {{ number_format($grn->invoice_amount, 2) }}</span>
                        </div>

                        <!-- Hidden Data for Modal -->
                        <textarea id="grn-data-{{ $grn->id }}" class="hidden">{{ json_encode([
                    'id' => $grn->id,
                    'grn_number' => $grn->grn_number,
                    'created_at' => $grn->created_at->format('F d, Y h:i A'),
                    'invoice_number' => $grn->invoice_number,
                    'invoice_amount' => number_format($grn->invoice_amount, 2),
                    'notes' => $grn->notes,
                    'supplier' => [
                        'name' => $grn->supplier->name ?? '',
                        'email' => $grn->supplier->primaryContact->email ?? '',
                        'phone' => $grn->supplier->primaryContact->phone ?? '',
                    ],
                    'items' => $grn->stockIns->map(function ($stockIn) {
                        // Map Quality Status
                        $qualityStatus = 'Unknown';
                        $qualityClass = 'bg-gray-100 text-gray-800';
                        
                        // Use valid CommonVariables constants
                        if (isset($stockIn->quality_check)) {
                            switch ($stockIn->quality_check) {
                                case \App\CommonVariables::$passedQuality:
                                    $qualityStatus = 'Passed';
                                    $qualityClass = 'bg-green-100 text-green-800 border-green-200';
                                    break;
                                case \App\CommonVariables::$partiallyQuality:
                                    $qualityStatus = 'Partial';
                                    $qualityClass = 'bg-orange-100 text-orange-800 border-orange-200';
                                    break;
                                case \App\CommonVariables::$failedQuality:
                                    $qualityStatus = 'Failed';
                                    $qualityClass = 'bg-red-100 text-red-800 border-red-200';
                                    break;
                            }
                        }

                        return [
                            'product_name' => $stockIn->productItem->product_name ?? 'Unknown',
                            'quantity' => $stockIn->added_quantity,
                            'cost' => number_format($stockIn->costing_price, 2),
                            'total' => number_format($stockIn->added_quantity * $stockIn->costing_price, 2),
                            'barcodes' => $stockIn->stock ? $stockIn->stock->barcodes->pluck('barcode')->toArray() : [],
                            'quality_status' => $qualityStatus,
                            'quality_class' => $qualityClass
                        ];
                    })
                ]) }}</textarea>
                    </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-center text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <inbox x="2" y="2" width="20" height="20" rx="2" ry="2"></inbox>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No GRNs Found</h3>
                    <p class="mt-1">Try adjusting your search or create a new GRN.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $grns->links() }}
        </div>
    </div>

    <!-- Modal -->
    <div id="grnModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeGrnModal()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all w-full max-w-6xl">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
                        <div>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-hexagon text-blue-600 text-xl font-bold"></i>
                                <h3 class="text-xl font-bold text-gray-900" id="modalGrnNumber">GRN Details</h3>
                            </div>
                            <p class="text-sm text-gray-500" id="modalDate"></p>
                        </div>
                        <button onclick="closeGrnModal()" class="text-gray-400 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-green-50 rounded-xl border border-gray-100">
                            <div>
                                <div class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Supplier
                                </div>
                                <div class="font-medium text-gray-900 text-lg" id="modalSupplierName"></div>
                                <div class="text-sm text-blue-600" id="modalSupplierContact"></div>
                            </div>
                            <div class="md:text-right">
                                <div class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Invoice
                                    Details</div>
                                <div class="font-medium text-gray-900" id="modalInvoiceNumber"></div>
                                <div class="text-2xl font-bold text-gray-900 mt-1" id="modalInvoiceAmount"></div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div>
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <i class="bi bi-box-seam text-purple-600 text-xl font-bold"></i>
                                Received Items
                            </h4>
                            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                                <table class="w-full text-sm text-left">
                                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 font-medium">Product</th>
                                            <th class="px-4 py-3 font-medium text-center">Quantity</th>
                                            <th class="px-4 py-3 font-medium text-center">Cost</th>
                                            <th class="px-4 py-3 font-medium text-center">Total</th>
                                            <th class="px-4 py-3 font-medium">Barcodes</th>
                                            <th class="px-4 py-3 font-medium">Quality Check</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modalItemsBody" class="divide-y divide-gray-100">
                                        <!-- Populated by JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div id="modalNotesSection" class="hidden">
                            <h4 class="font-bold text-gray-900 mb-2">Notes</h4>
                            <p class="text-gray-600 bg-yellow-50 p-3 rounded-lg border border-yellow-100 text-sm"
                                id="modalNotes"></p>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 flex justify-end">
                        <button type="button" onclick="closeGrnModal()"
                            class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                            Close
                        </button>
                        <!-- Future: Print GRN Button -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openGrnModal(id) {
            const dataElement = document.getElementById(`grn-data-${id}`);
            if (!dataElement) return;

            const data = JSON.parse(dataElement.value);

            // Header
            document.getElementById('modalGrnNumber').textContent = data.grn_number;
            document.getElementById('modalDate').textContent = data.created_at;

            // Info
            document.getElementById('modalSupplierName').textContent = data.supplier.name;
            document.getElementById('modalSupplierContact').textContent = `${data.supplier.email} • ${data.supplier.phone}`;
            document.getElementById('modalInvoiceNumber').textContent = `Inv #${data.invoice_number}`;
            document.getElementById('modalInvoiceAmount').textContent = `Rs ${data.invoice_amount}`;

            // Notes
            const notesSection = document.getElementById('modalNotesSection');
            if (data.notes) {
                notesSection.classList.remove('hidden');
                document.getElementById('modalNotes').textContent = data.notes;
            } else {
                notesSection.classList.add('hidden');
            }

            // Items
            const tbody = document.getElementById('modalItemsBody');
            tbody.innerHTML = '';

            data.items.forEach(item => {
                let barcodeHtml = '';
                if (item.barcodes && item.barcodes.length > 0) {
                    if (item.barcodes.length > 3) {
                        // Collapsed view if many barcodes
                        barcodeHtml = `
                                        <div class="flex flex-wrap gap-1">
                                            ${item.barcodes.slice(0, 3).map(b => `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">${b}</span>`).join('')}
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">+${item.barcodes.length - 3} more</span>
                                        </div>
                                     `;
                    } else {
                        barcodeHtml = `
                                        <div class="flex flex-wrap gap-1">
                                            ${item.barcodes.map(b => `<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">${b}</span>`).join('')}
                                        </div>
                                     `;
                    }
                } else {
                    barcodeHtml = '<span class="text-gray-400 text-xs italic">None</span>';
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                                <td class="px-4 py-3 font-medium text-gray-900">${item.product_name}</td>
                                <td class="px-4 py-3 text-center text-gray-600">${item.quantity}</td>
                                <td class="px-4 py-3 text-right text-gray-600">Rs ${item.cost}</td>
                                <td class="px-4 py-3 text-right font-medium text-purple-600">Rs ${item.total}</td>
                                <td class="px-2 py-3">${barcodeHtml}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border ${item.quality_class}">
                                        ${item.quality_status}
                                    </span>
                                </td>
                            `;
                tbody.appendChild(tr);
            });
            // Show
            document.getElementById('grnModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeGrnModal() {
            document.getElementById('grnModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
    </script>
@endsection