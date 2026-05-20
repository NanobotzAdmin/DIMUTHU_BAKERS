<?php

namespace App\Http\Controllers;

use App\CommonVariables;
use App\Models\AdAgent;
use App\Models\PmProductItem;
use App\Models\StmBarcode;
use App\Models\StmBarcodesHistory;
use App\Models\StmBranchStock;
use App\Models\StmOrderRequest;
use App\Models\StmOrderRequestHasPayment;
use App\Models\StmOrderRequestHasProduct;
use App\Models\StmOrderRequestHistory;
use App\Models\StmStockTransfer;
use App\Models\AdAgentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiGRNController extends Controller
{
    // private function getAgentId()
    // {
    //     $user = auth()->user();
    //     if ($user && $user->user_role_id == 8) {
    //         $agent = AdAgent::where('user_id', $user->id)->first();
    //         return $agent ? $agent->id : null;
    //     }
    //     return null;
    // }

    public function getProducts(Request $request)
    {
        try {
            $search = $request->query('search');

            // Get products with product_type_id = 3 (Bakery Staff) that have stock
            $query = PmProductItem::with(['stocks', 'category']);

            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('product_name', 'like', "%{$search}%")
                      ->orWhere('reference_number', 'like', "%{$search}%");
                });
            }

            $products = $query->get()
                ->map(function ($product) {
                    $latestStock = $product->stocks->first();
                    return [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'reference_number' => $product->reference_number,
                        'category' => $product->category->category_name ?? 'Uncategorized',
                        'pm_product_category_id' => $product->pm_product_category_id,
                        'selling_price' => (float) ($product->selling_price ?? 0),
                        'wholesale_price' => (float) ($product->wholesale_price ?? 0),
                        'distributor_price' => (float) ($product->distributor_price ?? $product->selling_price),
                        'cost_price' => $latestStock ? $latestStock->costing_price : 0,
                        'available_qty' => $product->stocks->sum('quantity') ?: 0,
                    ];
                });

            // Fetch all active categories to return to frontend
            $categories = \App\Models\PmProductCategory::where('is_active', 1)
                ->select('id', 'category_name')
                ->get()
                ->map(function($cat) {
                    return [
                        'id' => (string)$cat->id,
                        'label' => $cat->category_name,
                        'emoji' => '📦' // Default emoji as backend doesn't store emojis
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $products,
                'categories' => $categories
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Order Request Products Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch products'
            ], 500);
        }
    }

    public function index()
    {
        try {
            $agentId = $this->getAgentId();
            $orders = StmOrderRequest::where('agent_id', $agentId)
                ->with(['products', 'orderProducts', 'payments'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Order Requests Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch order requests'
            ], 500);
        }
    }

    public function createOrderRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:pm_product_item,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $agentId = $this->getAgentId();
            $agent = AdAgent::find($agentId);

            // Check Credit Limit
            if ($agent && $agent->credit_limit > 0 && $agent->outstanding_balance > $agent->credit_limit) {
                $needToPay = $agent->outstanding_balance - $agent->credit_limit;
                return response()->json([
                    'status' => false,
                    'message' => 'Credit Limit Exceeded! You need to pay Rs. ' . number_format($needToPay, 2) . ' to create a new order.',
                    'need_to_pay' => $needToPay
                ], 403);
            }

            $orderNumber = 'OR-' . strtoupper(uniqid());

            // 1. Create Order Request
            $orderRequest = StmOrderRequest::create([
                'order_number' => $orderNumber,
                'branch_id' => 1, // Default or generic branch
                'agent_id' => $agentId,
                'order_type' => 4, //Agent order type in mobile app,
                'delivery_date' => $request->delivery_date,
                'status' => CommonVariables::$orderRequestPendingApproval,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            $grandTotal = 0;

            // 2. Add Products
            foreach ($request->items as $item) {
                $product = PmProductItem::find($item['product_id']);
                $priceToUse = $product->distributor_price ?? $product->selling_price;
                $subtotal = $item['quantity'] * $priceToUse;
                $grandTotal += $subtotal;

                StmOrderRequestHasProduct::create([
                    'stm_order_request_id' => $orderRequest->id,
                    'pm_product_item_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $priceToUse,
                    'subtotal' => $subtotal,
                ]);

                // Create Stock Transfer record (Requesting stage)
                StmStockTransfer::create([
                    'stm_order_request_id' => $orderRequest->id,
                    'pm_product_item_id' => $item['product_id'],
                    'requesting_quantity' => $item['quantity'],
                    'qty_in_unit' => $item['quantity'], // Assuming unit is piece for now or similar
                    'batch_number' => '', // To be filled during approval/dispatch
                ]);
            }

            // Update grand total
            $orderRequest->update(['grand_total' => $grandTotal]);

            // 3. Log History
            StmOrderRequestHistory::create([
                'order_request_id' => $orderRequest->id,
                'action' => 'Order Created',
                'status' => $orderRequest->status,
                'description' => 'Initial order request submitted via Mobile App.',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order Request created successfully',
                'data' => $orderRequest->load('products', 'orderProducts')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Request Creation Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to create order request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string',
            'notes' => 'nullable|string',
            'credit_note_ids' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $orderRequest = StmOrderRequest::findOrFail($id);

            // Validation: Prevent overpayment after accounting for pending payments
            $pendingAmount = StmOrderRequestHasPayment::where('stm_order_request_id', $orderRequest->id)
                ->where('status', 1) // Pending Approval
                ->sum('payment_amount');
            $remaining = $orderRequest->grand_total - ($orderRequest->paid_amount + $pendingAmount);
            if ($request->amount > $remaining + 0.01) { // Small epsilon for floating point
                 return response()->json([
                    'status' => false,
                    'message' => 'Payment amount (Rs. ' . number_format($request->amount, 2) . ') exceeds remaining balance after accounting for pending payments (Rs. ' . number_format($remaining, 2) . ')',
                ], 422);
            }

            // Create Agent Payment Header
            $agentPayment = AdAgentPayment::create([
                'agent_id' => $orderRequest->agent_id,
                'amount' => $request->amount,
                'payment_method' => $request->method == 'Cash' ? 1 : ($request->method == 'Card' ? 2 : ($request->method == 'Bank Transfer' ? 3 : 4)),
                'payment_date' => now(),
                'status' => 0, // Pending
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            // Handle Credit Note links
            if ($request->method == 'Credit Note' && !empty($request->credit_note_ids)) {
                \App\Models\AdCreditNote::whereIn('id', $request->credit_note_ids)
                    ->update([
                        'ad_agent_payment_id' => $agentPayment->id,
                        'status' => 3 // Used
                    ]);
            }

            $payment = StmOrderRequestHasPayment::create([
                'stm_order_request_id' => $orderRequest->id,
                'ad_agent_payment_id' => $agentPayment->id,
                'payment_amount' => $request->amount,
                'payment_method' => $request->method,
                'payment_date' => now(),
                'created_by' => auth()->id(),
                'status' => 1,
                'notes' => $request->notes,
            ]);

            // Status 1 is already default (Pending Approval)
            // Description updated to reflect pending status
            $historyDescription = "Payment of Rs. " . number_format($request->amount, 2) . " recorded via " . $request->method . " (Pending Approval). ID: " . $agentPayment->id;

            // Note: We no longer update orderRequest->paid_amount or agent balance here.
            // These will be updated ONLY when an admin approves the payment.

            // Log History
            StmOrderRequestHistory::create([
                'order_request_id' => $orderRequest->id,
                'action' => 'Payment Recorded',
                'status' => $orderRequest->status,
                'description' => $historyDescription,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Payment recorded successfully and pending approval.',
                'data' => $payment,
                'agent_payment_id' => $agentPayment->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add Order Payment Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to record payment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addBulkPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required|exists:ad_agent,id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|string',
            'notes' => 'nullable|string',
            'is_auto' => 'nullable|boolean',
            'distributions' => 'nullable|array',
            'credit_note_ids' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $agent = AdAgent::findOrFail($request->agent_id);

            // Calculate total pending payments for this agent to deduct from validation
            $totalPendingPayments = StmOrderRequestHasPayment::join('stm_order_requests', 'stm_order_request_has_payments.stm_order_request_id', '=', 'stm_order_requests.id')
                ->where('stm_order_requests.agent_id', $agent->id)
                ->where('stm_order_request_has_payments.status', 1) // Pending Approval
                ->sum('stm_order_request_has_payments.payment_amount');

            $effectiveOutstanding = $agent->outstanding_balance - $totalPendingPayments;

            // Validation: Prevent overpayment against total outstanding after accounting for pending payments
            if ($request->amount > $effectiveOutstanding + 0.01) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment amount (Rs. ' . number_format($request->amount, 2) . ') exceeds total outstanding balance after accounting for pending payments (Rs. ' . number_format($effectiveOutstanding, 2) . ')',
                ], 422);
            }

            $totalAmount = $request->amount;
            $isAuto = $request->is_auto ?? false;
            $distInput = $request->distributions ?? [];
            $finalDistributions = [];

            if ($isAuto) {
                // Auto Distribution Logic: Find oldest outstanding orders
                $outstandingOrders = StmOrderRequest::where('agent_id', $request->agent_id)
                    ->whereIn('payment_completed', [0, 1, 3]) // 0: Pending, 1: Partial, 3: Credit
                    ->where('status', '>=', 1) // Approved or further
                    ->orderBy('created_at', 'asc')
                    ->get();

                $remainingAmount = $totalAmount;
                foreach ($outstandingOrders as $order) {
                    if ($remainingAmount <= 0.01) break;

                    $pendingAmount = StmOrderRequestHasPayment::where('stm_order_request_id', $order->id)
                        ->where('status', 1) // Pending Approval
                        ->sum('payment_amount');
                    $orderOutstanding = $order->grand_total - ($order->paid_amount + $pendingAmount);
                    if ($orderOutstanding <= 0) continue;

                    $paymentToApply = min($remainingAmount, $orderOutstanding);
                    $finalDistributions[] = [
                        'order_id' => $order->id,
                        'amount' => $paymentToApply
                    ];
                    $remainingAmount -= $paymentToApply;
                }
            } else {
                $finalDistributions = $distInput;
                
                // Validate manual distribution amounts against each order's actual outstanding after pending payments
                foreach ($finalDistributions as $dist) {
                    $order = StmOrderRequest::findOrFail($dist['order_id']);
                    $pendingAmount = StmOrderRequestHasPayment::where('stm_order_request_id', $order->id)
                        ->where('status', 1) // Pending Approval
                        ->sum('payment_amount');
                    $orderOutstanding = $order->grand_total - ($order->paid_amount + $pendingAmount);

                    if ($dist['amount'] > $orderOutstanding + 0.01) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Distributed amount for Order #' . $order->order_number . ' (Rs. ' . number_format($dist['amount'], 2) . ') exceeds remaining outstanding balance after accounting for pending payments (Rs. ' . number_format($orderOutstanding, 2) . ')',
                        ], 422);
                    }
                }
            }

            if (empty($finalDistributions)) {
                 return response()->json([
                    'status' => false,
                    'message' => 'No eligible orders found for payment distribution.'
                ], 400);
            }

            // Create Agent Payment Header
            $agentPayment = AdAgentPayment::create([
                'agent_id' => $agent->id,
                'amount' => $totalAmount,
                'payment_method' => $request->method == 'Cash' ? 1 : ($request->method == 'Card' ? 2 : ($request->method == 'Bank Transfer' ? 3 : 4)),
                'payment_date' => now(),
                'status' => 0, // Pending
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            // Handle Credit Note links
            if ($request->method == 'Credit Note' && !empty($request->credit_note_ids)) {
                \App\Models\AdCreditNote::whereIn('id', $request->credit_note_ids)
                    ->update([
                        'ad_agent_payment_id' => $agentPayment->id,
                        'status' => 3 // Used
                    ]);
            }

            foreach ($finalDistributions as $dist) {
                StmOrderRequestHasPayment::create([
                    'stm_order_request_id' => $dist['order_id'],
                    'ad_agent_payment_id' => $agentPayment->id,
                    'payment_amount' => $dist['amount'],
                    'payment_method' => $request->method,
                    'payment_date' => now(),
                    'created_by' => auth()->id(),
                    'status' => 1, // Pending Approval
                    'notes' => ($request->notes ?? '') . " (Bulk Distribution)",
                ]);

                // Log History for each order
                StmOrderRequestHistory::create([
                    'order_request_id' => $dist['order_id'],
                    'action' => 'Bulk Payment Recorded',
                    'status' => 1,
                    'description' => "Bulk Payment of Rs. " . number_format($dist['amount'], 2) . " via " . $request->method . " (Pending Approval). ID: " . $agentPayment->id,
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bulk payment recorded successfully and pending approval.',
                'distributed_to' => count($finalDistributions) . ' order(s)',
                'agent_payment_id' => $agentPayment->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk payment failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single order request detail with products, payments, and history.
     */
    public function show($id)
    {
        try {
            $order = StmOrderRequest::with([
                'orderProducts.productItem',
                'payments',
                'history' => function ($q) {
                    $q->with('user:id,first_name,last_name,user_name')->orderBy('created_at', 'asc');
                },
            ])->findOrFail($id);

            // Transform order_products to include product info at top level
            $orderData = $order->toArray();
            $orderData['order_products'] = collect($orderData['order_products'])->map(function ($op) {
                $op['product_id'] = $op['pm_product_item_id'];
                $op['product_name'] = $op['product_item']['product_name'] ?? null;
                $op['product'] = $op['product_item'] ?? null;
                unset($op['product_item']);
                return $op;
            })->toArray();

            return response()->json([
                'status' => true,
                'data' => $orderData,
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Order Request Detail Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch order detail',
            ], 500);
        }
    }

    /**
     * Confirm dispatched order – agent enters confirmed received quantities.
     */
    public function confirmOrder(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer|exists:stm_order_requests_has_product,id',
            'products.*.confirmed_quantity' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            $orderRequest = StmOrderRequest::findOrFail($id);

            $agentId = $orderRequest->agent_id;

            // Update confirmed quantities and link barcodes to branch stock
            foreach ($request->products as $item) {
                $orderProduct = StmOrderRequestHasProduct::find($item['id']);
                if (!$orderProduct) continue;

                $orderProduct->update(['confirmed_quantity' => $item['confirmed_quantity']]);

                // Find branch stock record for this order product
                $branchStock = StmBranchStock::where('stm_order_request_has_product_id', $item['id'])->first();
                
                if ($branchStock) {
                    $branchStock->update([
                        'status' => 1,
                        'updated_by' => auth()->id(),
                    ]);

                    // Update barcodes for this specific product in this order
                    $barcodes = StmBarcode::where('stm_order_requests_id', $orderRequest->id)
                        ->where('pm_product_item_id', $orderProduct->pm_product_item_id)
                        ->get();
                    
                    foreach ($barcodes as $barcode) {
                        $barcode->update([
                            'agent_id' => $agentId,
                            'stm_branch_stock_id' => $branchStock->id
                        ]);

                        StmBarcodesHistory::create([
                            'barcode_id' => $barcode->id,
                            'action' => 'Order Confirmed',
                            'description' => 'Agent confirmed order #' . $orderRequest->order_number . ' via Mobile App. Linked to branch stock ID: ' . $branchStock->id,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            // Update order status to Confirmed (7)
            $orderRequest->status = 7; // Complete Settled
            $orderRequest->updated_by = auth()->id();
            $orderRequest->save();

            // 4. Update Agent Balance and Total Sales
            if ($agent = AdAgent::find($agentId)) {
                // If the order was already recorded as a Credit order (agent_type 3 during approval),
                // we only update total_sales here, skip outstanding_balance to avoid double-counting.
                if ($orderRequest->payment_completed == 3) {
                    $agent->adjustBalance($orderRequest->grand_total, 'total_sales', true);
                } else {
                    $agent->adjustBalance($orderRequest->grand_total, 'outstanding_balance', true);
                    $agent->adjustBalance($orderRequest->grand_total, 'total_sales', true);
                }
            }

            // Log Order Request History
            StmOrderRequestHistory::create([
                'order_request_id' => $orderRequest->id,
                'action' => 'Dispatch Confirmed',
                'status' => 7, // Complete Settled
                'description' => 'Agent confirmed received quantities via Mobile App.',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order confirmed successfully',
                'data' => $orderRequest->load('orderProducts', 'payments', 'history'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Confirm Order Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to confirm order: ' . $e->getMessage(),
            ], 500);
        }
    }
}
