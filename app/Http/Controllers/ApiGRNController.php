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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApiGRNController extends Controller
{
    private function getAgentId()
    {
        $user = auth()->user();
        if ($user && $user->user_role_id == 8) {
            $agent = AdAgent::where('user_id', $user->id)->first();
            return $agent ? $agent->id : null;
        }
        return null;
    }

    public function getProducts()
    {
        try {
            // Get products with product_type_id = 3 (Bakery Staff) that have stock
            // joining with pm_product_item_has_product_types
            $products = PmProductItem::whereHas('productTypes', function ($query) {
                $query->where('pm_product_type.id', 3);
            })
                ->with(['stocks'])
                ->get()
                ->map(function ($product) {
                    // Get the earliest expiring stock or just the first available for price
                    $latestStock = $product->stocks->first();
                    return [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'reference_number' => $product->reference_number,
                        'selling_price' => (float) ($product->selling_price ?? 0),
                        'cost_price' => $latestStock ? $latestStock->costing_price : 0,
                        'available_qty' => $product->stocks->sum('quantity') ?: 0,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $products
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
                $subtotal = $item['quantity'] * $product->selling_price;
                $grandTotal += $subtotal;

                StmOrderRequestHasProduct::create([
                    'stm_order_request_id' => $orderRequest->id,
                    'pm_product_item_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
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

            $payment = StmOrderRequestHasPayment::create([
                'stm_order_request_id' => $orderRequest->id,
                'payment_amount' => $request->amount,
                'payment_method' => $request->method,
                'payment_date' => now(),
                'created_by' => auth()->id(),
                'status' => 1,
                'notes' => $request->notes,
            ]);

            // Check if fully paid and update status
            $totalPaid = StmOrderRequestHasPayment::where('stm_order_request_id', $orderRequest->id)->sum('payment_amount');

            $historyDescription = "Payment of Rs. " . number_format($request->amount, 2) . " recorded via " . $request->method;

            // Update order request payment info
            $orderRequest->paid_amount = $totalPaid;

            if ($totalPaid >= $orderRequest->grand_total) {
                $orderRequest->payment_completed = 2; // Paid
                // Also update order status if needed, the user mentioned 7 for completed in frontend logic usually
                // but kept the existing status = 2 logic if that's what was there. 
                // However, common variables might be better. 
                // Let's stick to what's requested: payment_completed = 2
                $historyDescription .= ". Order marked as fully paid and completed.";
            } else if ($totalPaid > 0) {
                $orderRequest->payment_completed = 1; // Partial
                $historyDescription .= ". Order marked as partially paid.";
            }
            
            $orderRequest->save();

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
                'message' => 'Payment recorded successfully' . ($orderRequest->status == 2 ? ' and order marked as completed' : ''),
                'data' => $payment
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

            // Update each product's confirmed quantity
            foreach ($request->products as $item) {
                StmOrderRequestHasProduct::where('id', $item['id'])
                    ->where('stm_order_request_id', $orderRequest->id)
                    ->update(['confirmed_quantity' => $item['confirmed_quantity']]);

                // Update stm_branch_stock status for this order product
                StmBranchStock::where('stm_order_request_has_product_id', $item['id'])
                    ->update([
                        'status' => 1,
                        'updated_by' => auth()->id(),
                    ]);
            }

            // Update stm_barcodes agent_id and create history for each barcode
            $barcodes = StmBarcode::where('stm_order_requests_id', $orderRequest->id)->get();
            foreach ($barcodes as $barcode) {
                $barcode->update(['agent_id' => $agentId]);

                StmBarcodesHistory::create([
                    'barcode_id' => $barcode->id,
                    'action' => 'Order Confirmed',
                    'description' => 'Agent confirmed order #' . $orderRequest->order_number . ' via Mobile App.',
                    'created_by' => auth()->id(),
                ]);
            }

            // Update order status to Confirmed (3)
            $orderRequest->status = 7; // Complete Settled
            $orderRequest->updated_by = auth()->id();
            $orderRequest->save();

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
