<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PosManagementController extends Controller
{
    public function posIndex()
    {
        return view('pos.index');
    }

    public function getPosData(Request $request)
    {
        try {
            // 1. Get Selected Branch from User (Auth)
            $user = auth()->user();
            $branchId = $user->current_branch_id;

            if (!$branchId) {
                // Fallback: If no branch selected, try to get from first assigned branch
                $branch = $user->branches->first();

                if ($branch) {
                    $branchId = $branch->id;
                    // Auto-save to user? Maybe not, just use it for this request.
                    // But if we want consistent experience:
                    $user->current_branch_id = $branch->id;
                    $user->save();
                } else {
                    Log::warning('User ' . $user->id . ' has no assigned branch.');
                    return response()->json(['success' => false, 'message' => 'No branch assigned to user.'], 403);
                }
            }

            // 2. Fetch Categories (Product Types)
            $categories = \App\Models\PmProductType::where('status', '1') // Assuming '1' is active
                ->pluck('product_type_name')
                ->prepend('All'); // Add 'All' as the first option

            // 3. Fetch Branch Stock with Product Details
            $stockItems = \App\Models\StmBranchStock::with([
                'productItem',
                'productItem.productTypes',
                'stock' // To get selling price
            ])
                ->where('um_branch_id', $branchId)
                ->where('status', '1') // Active stock
                ->whereHas('productItem', function ($q) {
                    $q->where('status', '1');
                })
                ->get();

            // 4. Transform Data
            $products = $stockItems->map(function ($item) {
                $productItem = $item->productItem;
                $stock = $item->stock;

                // Determine Category (First associated type or 'Others')
                $category = $productItem->productTypes->first()->product_type_name ?? 'Others';

                // Determine Price (Prioritize stock selling price, fallback to 0)
                $price = $stock ? $stock->selling_price : 0; // Or fetch from productItem if available

                // Generic Image or specific if available (Assuming image field exists or using placeholder)
                // $image = $productItem->image_path ? asset($productItem->image_path) : null;

                return [
                    'id' => $productItem->id, // Use Product Item ID for cart grouping
                    'name' => $productItem->product_name,
                    'category' => $category,
                    'sellingPrice' => (float) $price,
                    'sku' => $productItem->reference_number ?? $productItem->product_code ?? 'N/A', // Adjust field names
                    'stockLevel' => (int) $item->quantity,
                    'lowStockThreshold' => 10, // Hardcoded or fetch from config/DB
                    // 'image' => $image 
                ];
            });

            return response()->json([
                'success' => true,
                'categories' => $categories,
                'products' => $products
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load POS data: ' . $e->getMessage()
            ], 500);
        }
    }
    public function searchCustomers(Request $request)
    {
        try {
            $query = $request->input('query');
            $customers = \App\Models\CmCustomer::query() // Active check removed as column might not exist
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->where('customer_type', \App\CommonVariables::$customerTypePOS)
                ->limit(20)
                ->get(['id', 'name', 'phone', 'email', 'address']); // Select needed fields

            return response()->json([
                'success' => true,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeSale(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:pm_product_item,id',
            'cart.*.quantity' => 'required|integer|min:1',
            'cart.*.price' => 'required|numeric|min:0',
            'paymentMethods' => 'required|array|min:1',
            'totals' => 'required|array',
            'totals.subtotal' => 'required|numeric',
            'totals.tax' => 'required|numeric',
            'totals.total' => 'required|numeric',
            'totals.discount' => 'nullable|numeric',
            'totals.discountType' => 'nullable|integer|in:1,2',
            'customerId' => 'nullable|exists:cm_customer,id',
        ]);

        $user = auth()->user();
        if (!$user || !$user->current_branch_id) {
            return response()->json(['success' => false, 'message' => 'User branch not defined'], 403);
        }
        $branchId = $user->current_branch_id;

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 2. Create Invoice
            $invoiceNumber = 'INV-' . $branchId . '-' . time() . '-' . rand(100, 999); // Simple generation
            $invoice = \App\Models\SoInvoice::create([
                'invoice_number' => $invoiceNumber,
                'total_price' => $validated['totals']['subtotal'], // Base price before tax/discount? Usually total. Let's use total payable.
                // Actually structure has: total_price (gross?), tax_amount, discount_value, payble_amount
                'total_price' => $validated['totals']['subtotal'], // Using subtotal as base
                'tax_amount' => $validated['totals']['tax'],
                'discount_type' => $validated['totals']['discountType'] ?? 1, // Default Percentage (1) if null
                'discount_value' => $validated['totals']['discount'] ?? 0,
                'payble_amount' => $validated['totals']['total'],
                'given_amount' => collect($validated['paymentMethods'])->sum('amount'), // Total paid
                'um_branch_id' => $branchId,
                'cm_customer_id' => $validated['customerId'] ?? null,
                'status' => 1,
                'created_by' => $user->id,
            ]);

            // 3. Process Cart Items (Stock & Barcodes)
            foreach ($validated['cart'] as $item) {
                $productItemId = $item['id'];
                $qty = $item['quantity'];
                $price = $item['price'];

                // A. Check & Update Stock
                // We need to find the specific branch stock record.
                // NOTE: logic to find the correct StmBranchStock record might be complex if multiple batches exist. 
                // For simplified POS, we assume one aggregated record or take the first active one.
                // Index has: $stockItems fetching by um_branch_id and pm_product_item_id.

                $branchStock = \App\Models\StmBranchStock::where('um_branch_id', $branchId)
                    ->where('pm_product_item_id', $productItemId)
                    ->where('status', '1')
                    ->first();

                if (!$branchStock) {
                    throw new \Exception("Stock not found for product ID {$productItemId}");
                }

                if ($branchStock->quantity < $qty) {
                    throw new \Exception("Insufficient stock for product ID {$productItemId}. Available: {$branchStock->quantity}");
                }

                // Decrement Stock
                $branchStock->quantity -= $qty;
                $branchStock->save();

                // B. Barcode Logic
                // Find 'qty' number of unsold barcodes for this product/branch
                // We assume barcodes are linked to stm_stock which is linked to stm_branch_stock???
                // Or directly by pm_product_item_id and um_branch_id if available.
                // StmBarcode has um_branch_id field.

                $barcodes = \App\Models\StmBarcode::where('um_branch_id', $branchId)
                    ->where('pm_product_item_id', $productItemId)
                    ->where('is_sold', '0') // Unsold
                    ->orderBy('id', 'asc') // FIFO
                    ->limit($qty)
                    ->get();

                // It's possible we don't track barcodes for ALL items, or count mismatches.
                // If barcodes exist, mark them sold.
                foreach ($barcodes as $barcode) {
                    $barcode->is_sold = '1';
                    $barcode->save();

                    // Record History
                    \App\Models\StmBarcodesHistory::create([
                        'barcode_id' => $barcode->id,
                        'created_by' => $user->id,
                        'action' => 'SOLD',
                        'description' => 'Product sold via POS. Invoice: ' . $invoiceNumber
                    ]);
                }

                // C. Invoice Has Stock (Line Item)
                \App\Models\SoInvoiceHasStock::create([
                    'so_invoice_id' => $invoice->id,
                    'pm_product_item_id' => $productItemId,
                    'um_branch_id' => $branchId,
                    'stm_branch_stock_id' => $branchStock->id,
                    'stock_date' => now(), // or stock created date
                    'grn_price' => 0, // Need to fetch cost/GRN price if needed for profit calc
                    'selling_price' => $price,
                    'invoiced_price' => $price, // Price sold at
                    'qty' => $qty,
                    'is_active' => 1,
                    'created_by' => $user->id
                ]);
            }
            // 4. Payments
            foreach ($validated['paymentMethods'] as $payment) {
                // Frontend now sends integer 'type' directly (1=Cash, 2=Card, 3=Bank)
                \App\Models\SoPayments::create([
                    'so_invoice_id' => $invoice->id,
                    'paid_amount' => $payment['amount'],
                    'payment_type' => $payment['type'], // Integer from frontend
                    'card_4_digits' => $payment['card_4_digits'] ?? null,
                    'transaction_id' => $payment['transaction_id'] ?? null,
                    'reference' => $payment['reference'] ?? null,
                    'gift_card_code' => $payment['gift_card_code'] ?? null,
                    'status' => 1,
                    'created_by' => $user->id,
                    'updated_by' => $user->id
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully',
                'invoice' => $invoice
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeCustomer(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20|unique:cm_customer,phone',
            ]);

            $customer = \App\Models\CmCustomer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'customer_type' => \App\CommonVariables::$customerTypePOS, // POS Customer Type
                // 'is_active' => '1', // Removed
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function getTabPartial($view)
    {
        // Map frontend tab IDs to View Partials
        $map = [
            'pos' => 'pos',
            'online-pickup' => 'pickup',
            'orders' => 'orders',
            'history' => 'history',
            'returns' => 'returns',
            'cash-recon' => 'recon',
            'shift-report' => 'report'
        ];

        if (!array_key_exists($view, $map)) {
            return response()->json(['error' => 'Invalid tab'], 400);
        }

        $partialName = $map[$view];
        return view("pos.tabs.{$partialName}");
    }

    public function getPickupOrders()
    {
        // Mock Data
        $orders = [
            [
                'id' => '1',
                'orderNumber' => 'ORD-001',
                'status' => 'pending',
                'createdAt' => now()->toIso8601String(),
                'pickup' => ['scheduledTime' => '10:30 AM', 'outletName' => 'Main Branch', 'instructions' => ''],
                'customer' => ['name' => 'John Doe', 'phone' => '0771234567', 'email' => 'john@example.com'],
                'payment' => ['method' => 'online', 'status' => 'paid'],
                'summary' => ['total' => 1500.00],
                'items' => [
                    ['id' => 1, 'quantity' => 2, 'productName' => 'Chicken Bun', 'subtotal' => 300],
                    ['id' => 2, 'quantity' => 1, 'productName' => 'Iced Coffee', 'subtotal' => 1200]
                ],
                'customerNotes' => 'Extra spicy please'
            ],
            [
                'id' => '2',
                'orderNumber' => 'ORD-002',
                'status' => 'confirmed',
                'createdAt' => now()->toIso8601String(),
                'pickup' => ['scheduledTime' => '11:00 AM', 'outletName' => 'Main Branch', 'instructions' => ''],
                'customer' => ['name' => 'Jane Smith', 'phone' => '0719876543', 'email' => 'jane@example.com'],
                'payment' => ['method' => 'cash-on-pickup', 'status' => 'pending'],
                'summary' => ['total' => 2500.00],
                'items' => [
                    ['id' => 3, 'quantity' => 1, 'productName' => 'Chocolate Cake', 'subtotal' => 2500]
                ],
                'customerNotes' => ''
            ]
        ];
        return response()->json($orders);
    }

    public function getIncomingOrders()
    {
        // Mock Data
        $orders = [
            [
                'id' => '101',
                'orderNumber' => 'ORD-8852',
                'orderType' => 'delivery',
                'status' => 'pending',
                'customerName' => 'Amara Perera',
                'customerPhone' => '077-1234567',
                'deliveryDate' => now()->toIso8601String(),
                'deliveryAddress' => '123 Galle Road, Colombo 03',
                'items' => [
                    ['productName' => 'Chicken Fried Rice', 'quantity' => 2, 'unitPrice' => 850, 'lineTotal' => 1700],
                    ['productName' => 'Chilli Paste', 'quantity' => 1, 'unitPrice' => 350, 'lineTotal' => 350]
                ],
                'total' => 2050.00,
                'notes' => 'Less oil please'
            ],
            [
                'id' => '102',
                'orderNumber' => 'ORD-8853',
                'orderType' => 'pickup',
                'status' => 'preparing',
                'customerName' => 'Kamal Silva',
                'pickupLocation' => 'Main Outlet',
                'deliveryDate' => now()->addDay()->toIso8601String(),
                'items' => [
                    ['productName' => 'Chocolate Cake', 'quantity' => 1, 'unitPrice' => 4500, 'lineTotal' => 4500]
                ],
                'total' => 4500.00
            ]
        ];
        return response()->json($orders);
    }

    public function getTransactionHistory()
    {
        try {
            $user = auth()->user();
            $branchId = $user->current_branch_id;

            $invoices = \App\Models\SoInvoice::with([
                'items.productItem',
                'payments',
                'customer',
                'creator'
            ])
                ->where('um_branch_id', $branchId)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get();

            $history = $invoices->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'receiptNumber' => $inv->invoice_number,
                    'timestamp' => $inv->created_at->toIso8601String(),
                    'cashier' => $inv->creator ? $inv->creator->first_name . ' ' . $inv->creator->last_name : 'Unknown',
                    'customer' => $inv->customer ? [
                        'name' => $inv->customer->name,
                        'phone' => $inv->customer->phone
                    ] : null,
                    'items' => $inv->items->map(function ($item) {
                        return [
                            'productName' => $item->productItem->product_name ?? 'Unknown Item',
                            'quantity' => (float) $item->qty,
                            'unitPrice' => (float) $item->invoiced_price,
                            'lineTotal' => (float) ($item->invoiced_price * $item->qty)
                        ];
                    }),
                    'subtotal' => (float) $inv->total_price, // Assuming total_price stored is subtotal-ish base
                    'tax' => (float) $inv->tax_amount,
                    'discount' => (float) $inv->discount_value,
                    'discountType' => (int) $inv->discount_type,
                    'total' => (float) $inv->payble_amount,
                    'paymentMethods' => $inv->payments->map(function ($pm) {
                        // Map int back to string for UI if needed, or send both
                        $method = 'Unknown';
                        switch ($pm->payment_type) {
                            case 1:
                                $method = 'cash';
                                break;
                            case 2:
                                $method = 'card';
                                break;
                            case 3:
                                $method = 'bank';
                                break;
                            case 4:
                                $method = 'credit';
                                break;
                            case 5:
                                $method = 'gift';
                                break;
                        }
                        return [
                            'method' => $method,
                            'amount' => (float) $pm->paid_amount
                        ];
                    })
                ];
            });

            return response()->json($history);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showReceipt($id)
    {
        $invoice = \App\Models\SoInvoice::with(['items.productItem', 'payments', 'customer', 'creator'])
            ->findOrFail($id);

        return view('pos.receipt', compact('invoice'));
    }

    public function getReturns()
    {
        // Mock Data
        $returns = [
            [
                'id' => 'TXN-8851',
                'receiptNumber' => 'REC-001',
                'timestamp' => now()->toIso8601String(),
                'cashier' => 'Kasun',
                'customer' => ['name' => 'Amara Perera'],
                'subtotal' => 1500.00,
                'tax' => 0.00,
                'total' => 1500.00,
                'items' => [
                    ['productName' => 'Chicken Bun', 'productSKU' => 'BAK-001', 'quantity' => 2, 'unitPrice' => 150, 'lineTotal' => 300],
                    ['productName' => 'Iced Coffee', 'productSKU' => 'BEV-002', 'quantity' => 1, 'unitPrice' => 1200, 'lineTotal' => 1200]
                ],
                'paymentMethods' => [['method' => 'cash', 'amount' => 1500]]
            ]
        ];
        return response()->json($returns);
    }

    public function getReconciliationData()
    {
        $transactions = [
            ['paymentMethods' => [['method' => 'cash', 'amount' => 5000]]],
            ['paymentMethods' => [['method' => 'card', 'amount' => 2500]]],
            ['paymentMethods' => [['method' => 'cash', 'amount' => 1500]]]
        ];
        return response()->json($transactions);
    }

    public function getShiftReportData()
    {
        // Generate mock data on the fly (simplified version of JS logic)
        $txns = [];
        $products = ['Chicken Bun', 'Iced Coffee', 'Fish Bun', 'Tea', 'Cake Slice', 'Rolls'];

        for ($i = 0; $i < 50; $i++) {
            $date = now()->subSeconds(rand(0, 30 * 24 * 60 * 60));
            if ($i < 10)
                $date = now(); // Ensure some today

            $total = 500 + rand(0, 4500);
            $method = (rand(0, 10) > 4) ? 'cash' : ((rand(0, 10) > 5) ? 'card' : 'online');

            $txns[] = [
                'id' => "TX-$i",
                'timestamp' => $date->toIso8601String(),
                'total' => $total,
                'discount' => (rand(0, 10) > 7) ? 100 : 0,
                'status' => (rand(0, 100) > 95) ? 'returned' : 'completed',
                'paymentMethods' => [['method' => $method, 'amount' => $total]],
                'items' => [
                    ['productName' => $products[array_rand($products)], 'quantity' => rand(1, 3), 'lineTotal' => $total / 2],
                    ['productName' => $products[array_rand($products)], 'quantity' => 1, 'lineTotal' => $total / 2]
                ]
            ];
        }
        return response()->json($txns);
    }
}
