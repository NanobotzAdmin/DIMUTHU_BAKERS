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
            'delivery_date' => 'nullable|date',
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

        // Check if today is holiday or Sunday
        if (\App\Helpers\DeliveryDateHelper::isHolidayOrSunday(now())) {
            return response()->json([
                'status' => false,
                'message' => 'Today is a holiday or Sunday. Order placement is closed.',
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

            if (!$request->filled('delivery_date')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Delivery date is required.',
                ], 422);
            }

            $selectedDatetime = \Carbon\Carbon::parse($request->delivery_date);

            // If selected date is holiday/Sunday, block it
            if (\App\Helpers\DeliveryDateHelper::isHolidayOrSunday($selectedDatetime)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Delivery/Collection cannot be scheduled on Sundays or Holidays.',
                ], 422);
            }

            // Calculate min delivery date/time
            $minDelivery = \App\Helpers\DeliveryDateHelper::calculateDeliveryDate(now());

            if ($selectedDatetime->lt($minDelivery)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Earliest possible delivery for this order is ' . $minDelivery->format('F d, Y at h:i A') . '.',
                ], 422);
            }

            // 1. Create Order Request
            $orderRequest = StmOrderRequest::create([
                'order_number' => $orderNumber,
                'branch_id' => 1, // Default or generic branch
                'agent_id' => $agentId,
                'order_type' => 4, //Agent order type in mobile app,
                'delivery_date' => $selectedDatetime,
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

            // Queue Emails for active recipients (process_id = 1)
            try {
                $recipients = \App\Models\EmProcessHasEmailAddress::where('process_id', 1)
                    ->where('status', 1)
                    ->get();

                if ($recipients->isNotEmpty()) {
                    $agentName = $agent ? $agent->agent_name : ('Agent #' . $agentId);
                    
                    // Retrieve logo from system settings
                    $logoUrl = asset('images/logo.png');
                    $systemConfigPath = public_path('system_config.json');
                    if (file_exists($systemConfigPath)) {
                        $systemSettings = json_decode(file_get_contents($systemConfigPath), true);
                        if (!empty($systemSettings['logos']['primary'])) {
                            $logoUrl = asset($systemSettings['logos']['primary']);
                        }
                    }

                    $orderItemsHtml = '';
                    foreach ($request->items as $item) {
                        $product = PmProductItem::find($item['product_id']);
                        $priceToUse = $product->distributor_price ?? $product->selling_price;
                        $subtotal = $item['quantity'] * $priceToUse;
                        $orderItemsHtml .= '<tr>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; color: #333333; font-weight: 500;">' . htmlspecialchars($product->product_name) . '</td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; text-align: right; color: #495057; font-weight: bold;">' . number_format($item['quantity'], 2) . '</td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; text-align: right; color: #495057;">Rs. ' . number_format($priceToUse, 2) . '</td>
                            <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; text-align: right; color: #1a1a1a; font-weight: 600;">Rs. ' . number_format($subtotal, 2) . '</td>
                        </tr>';
                    }

                    $notesSection = !empty($request->notes) 
                        ? '<div style="margin-top: 25px; padding: 18px; background: #faf8f5; border-left: 4px solid #b89755; border-radius: 4px; font-style: italic; color: #5a4b31; font-size: 14px; line-height: 1.5;"><strong>Special Notes:</strong> ' . htmlspecialchars($request->notes) . '</div>' 
                        : '';

                    $emailContent = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Request Received</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: \'Inter\', sans-serif; color: #495057; background-color: #f6f8fb; margin: 0; padding: 40px 20px; -webkit-font-smoothing: antialiased; }
        .container { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #eef2f6; }
        .header { background: linear-gradient(135deg, #52381f, #2e1d0f); padding: 35px 30px; border-bottom: 4px solid #b89755; position: relative; }
        .header-content { display: flex; align-items: center; justify-content: center; gap: 20px; text-align: left; }
        .header img { max-height: 80px; width: auto; filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.15)); margin: 0; }
        .header-text-container { text-align: left; }
        .header h1 { margin: 0; font-family: \'Cinzel\', serif; font-size: 24px; font-weight: 600; color: #ffffff; letter-spacing: 1px; text-transform: uppercase; line-height: 1.2; }
        .header p { color: #b89755; margin: 5px 0 0 0; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; }
        .content { padding: 40px 35px; }
        .status-badge { display: inline-block; padding: 6px 14px; background-color: #fff9db; border: 1px solid #ffe066; color: #8a6d3b; font-weight: 600; font-size: 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; }
        .intro-title { font-family: \'Cinzel\', serif; font-size: 20px; font-weight: 600; color: #2e1d0f; margin-top: 0; margin-bottom: 10px; }
        .intro-p { font-size: 15px; color: #6c757d; line-height: 1.6; margin-bottom: 30px; }
        .order-meta { display: table; width: 100%; margin-bottom: 30px; border-bottom: 1px solid #eef2f6; padding-bottom: 25px; }
        .meta-col { display: table-cell; width: 50%; vertical-align: top; }
        .meta-label { color: #adb5bd; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .meta-val { color: #2e1d0f; font-size: 15px; font-weight: 600; }
        .table-container { border: 1px solid #eef2f6; border-radius: 8px; overflow: hidden; margin: 25px 0; }
        .table { width: 100%; border-collapse: collapse; margin: 0; }
        .table th { text-align: left; padding: 14px 16px; background-color: #f8fafc; border-bottom: 1px solid #eef2f6; color: #868e96; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .total-row { font-weight: bold; background-color: #fffaf0; }
        .total-label { font-family: \'Inter\', sans-serif; font-size: 14px; color: #2e1d0f; font-weight: 700; }
        .total-value { font-family: \'Inter\', sans-serif; font-size: 18px; color: #2e1d0f; font-weight: 700; }
        .footer { background: #f8fafc; text-align: center; padding: 30px; font-size: 12px; color: #868e96; border-top: 1px solid #eef2f6; line-height: 1.6; }
        .footer strong { color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <img src="' . htmlspecialchars($logoUrl) . '" alt="DIMUTHU BAKEHOUSE">
                <div class="header-text-container">
                    <h1>Dimuthu Bake House (Pvt) Ltd.</h1>
                    <p>Artisanal Excellence</p>
                </div>
            </div>
        </div>
        <div class="content">
            <span class="status-badge">Pending Approval</span>
            <h2 class="intro-title">New Order Request</h2>
            <p class="intro-p">A new order request has been successfully submitted and is now awaiting verification. Please review the details below.</p>
            
            <div class="order-meta">
                <div class="meta-col">
                    <div class="meta-label">Submitted By Agent</div>
                    <div class="meta-val">' . htmlspecialchars($agentName) . '</div>
                </div>
                <div class="meta-col" style="padding-left: 20px;">
                    <div class="meta-label">Delivery Target Date</div>
                    <div class="meta-val">' . htmlspecialchars(\Carbon\Carbon::parse($request->delivery_date)->format('F d, Y')) . '</div>
                </div>
            </div>

            <div class="order-meta" style="border-bottom: none; padding-bottom: 0; margin-bottom: 10px;">
                <div class="meta-col">
                    <div class="meta-label">Order Request ID</div>
                    <div class="meta-val" style="color: #b89755; font-family: monospace; font-size: 16px;">' . htmlspecialchars($orderNumber) . '</div>
                </div>
                <div class="meta-col" style="padding-left: 20px;">
                    <div class="meta-label">Submission Date</div>
                    <div class="meta-val">' . now()->format('F d, Y - h:i A') . '</div>
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="padding: 14px 16px;">Product Item</th>
                            <th style="padding: 14px 16px; text-align: right;">Qty</th>
                            <th style="padding: 14px 16px; text-align: right;">Unit Price</th>
                            <th style="padding: 14px 16px; text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $orderItemsHtml . '
                        <tr class="total-row">
                            <td colspan="3" class="total-label" style="padding: 18px 16px; border-top: 2px solid #eef2f6; text-align: right;">Grand Total:</td>
                            <td class="total-value" style="padding: 18px 16px; border-top: 2px solid #eef2f6; text-align: right;">Rs. ' . number_format($grandTotal, 2) . '</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            ' . $notesSection . '

            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                <a href="' . url('/order-management?search=' . urlencode($orderNumber)) . '" target="_blank" style="display: inline-block; padding: 14px 28px; background: linear-gradient(135deg, #b89755, #8a6d3b); color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 8px; box-shadow: 0 4px 12px rgba(184, 151, 85, 0.25); text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;">View Order Request</a>
            </div>
        </div>
        <div class="footer">
            &copy; ' . date('Y') . ' <strong>Dimuthu Bakehouse (Pvt) Ltd.</strong><br>
            527, Thewatta Road, Ragama, Sri Lanka.<br>
            <span style="font-size: 11px; margin-top: 10px; display: block; color: #adb5bd;">This is an automated notification. Please do not reply directly to this email.</span>
        </div>
    </div>
</body>
</html>';

                    foreach ($recipients as $recipient) {
                        \App\Models\EmEmailSend::create([
                            'email_address' => $recipient->email_address,
                            'process_id' => 1,
                            'email_subject' => 'New Order Request ' . $orderNumber . ' Submitted',
                            'email_content' => $emailContent,
                            'status' => 0, // Pending
                            'created_by' => auth()->id(),
                            'created_at' => now(),
                        ]);
                    }
                }
            } catch (\Exception $ex) {
                Log::error('Order Request Email Queueing Failed: ' . $ex->getMessage());
            }

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

            // Log History
            \App\Models\AdAgentPaymentHistory::create([
                'ad_agent_payment_id' => $agentPayment->id,
                'created_by' => auth()->id(),
                'action' => 'Payment Recorded',
                'status' => 0, // Pending
                'description' => 'Payment of Rs. ' . number_format($request->amount, 2) . ' recorded by Agent.',
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

            $this->sendPaymentEmailNotification($agentPayment);

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

            // Log History
            \App\Models\AdAgentPaymentHistory::create([
                'ad_agent_payment_id' => $agentPayment->id,
                'created_by' => auth()->id(),
                'action' => 'Bulk Payment Recorded',
                'status' => 0, // Pending
                'description' => 'Bulk payment of Rs. ' . number_format($totalAmount, 2) . ' recorded by Agent.',
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

            $this->sendPaymentEmailNotification($agentPayment);

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
                'rejectedByUser',
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

    public function validateOrderDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide a valid delivery date and time.',
            ], 422);
        }

        try {
            // Check if today is holiday or Sunday
            if (\App\Helpers\DeliveryDateHelper::isHolidayOrSunday(now())) {
                return response()->json([
                    'status' => false,
                    'message' => 'Today is a holiday or Sunday. Order placement is closed.',
                ]);
            }

            $selectedDatetime = \Carbon\Carbon::parse($request->delivery_date);

            // Check if selected day is Sunday or holiday
            if (\App\Helpers\DeliveryDateHelper::isHolidayOrSunday($selectedDatetime)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Delivery/Collection cannot be scheduled on Sundays or Holidays.',
                ]);
            }

            // Calculate min delivery date/time
            $minDelivery = \App\Helpers\DeliveryDateHelper::calculateDeliveryDate(now());

            if ($selectedDatetime->lt($minDelivery)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Earliest possible delivery for this order is ' . $minDelivery->format('F d, Y at h:i A') . '.',
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Delivery date is valid.',
            ]);

        } catch (\Exception $e) {
            Log::error('Validate Order Date Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to validate delivery date.',
            ], 500);
        }
    }

    public function getHolidays()
    {
        try {
            $holidays = \App\Models\Holiday::where('date', '>=', now()->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->get(['date', 'description']);

            return response()->json([
                'status' => true,
                'data' => $holidays,
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Holidays Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch holidays.',
            ], 500);
        }
    }

    public function getAgentPayments()
    {
        try {
            $agentId = $this->getAgentId();
            if (!$agentId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent profile not found.'
                ], 404);
            }

            $payments = AdAgentPayment::where('agent_id', $agentId)
                ->with(['distributions.orderRequest', 'history.creator', 'creditNotes'])
                ->orderBy('payment_date', 'desc')
                ->get();

            return response()->json([
                'status' => true,
                'data' => $payments
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch Agent Payments Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch payments.'
            ], 500);
        }
    }

    private function sendPaymentEmailNotification($agentPayment)
    {
        try {
            $recipients = \App\Models\EmProcessHasEmailAddress::where('process_id', 2)
                ->where('status', 1)
                ->get();

            if ($recipients->isNotEmpty()) {
                $agent = $agentPayment->agent;
                $agentName = $agent ? $agent->agent_name : ('Agent #' . $agentPayment->agent_id);
                $receiptNumber = 'REC-' . str_pad($agentPayment->id, 5, '0', STR_PAD_LEFT);
                $paymentMethodText = [1 => 'Cash', 2 => 'Card', 3 => 'Bank Transfer', 4 => 'Credit Note'][$agentPayment->payment_method] ?? 'Other';

                // Retrieve logo from system settings
                $logoUrl = asset('images/logo.png');
                $systemConfigPath = public_path('system_config.json');
                if (file_exists($systemConfigPath)) {
                    $systemSettings = json_decode(file_get_contents($systemConfigPath), true);
                    if (!empty($systemSettings['logos']['primary'])) {
                        $logoUrl = asset($systemSettings['logos']['primary']);
                    }
                }

                // Construct distributions HTML
                $distributionsHtml = '';
                $agentPayment->load('distributions.orderRequest');
                foreach ($agentPayment->distributions as $index => $dist) {
                    $orderNo = $dist->orderRequest->order_number ?? 'N/A';
                    $orderTotal = $dist->orderRequest->grand_total ?? 0;
                    $distributionsHtml .= '<tr>
                        <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; color: #333333; font-weight: 500;">#' . htmlspecialchars($orderNo) . '</td>
                        <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; text-align: right; color: #495057;">Rs. ' . number_format($orderTotal, 2) . '</td>
                        <td style="padding: 14px 16px; border-bottom: 1px solid #f1f3f7; text-align: right; color: #1a1a1a; font-weight: 600;">Rs. ' . number_format($dist->payment_amount, 2) . '</td>
                    </tr>';
                }

                $notesSection = !empty($agentPayment->notes) 
                    ? '<div style="margin-top: 25px; padding: 18px; background: #faf8f5; border-left: 4px solid #b89755; border-radius: 4px; font-style: italic; color: #5a4b31; font-size: 14px; line-height: 1.5;"><strong>Agent Notes:</strong> ' . htmlspecialchars($agentPayment->notes) . '</div>' 
                    : '';

                $emailContent = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Agent Payment Recorded</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: \'Inter\', sans-serif; color: #495057; background-color: #f6f8fb; margin: 0; padding: 40px 20px; -webkit-font-smoothing: antialiased; }
        .container { max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #eef2f6; }
        .header { background: linear-gradient(135deg, #52381f, #2e1d0f); padding: 35px 30px; border-bottom: 4px solid #b89755; position: relative; }
        .header-content { display: flex; align-items: center; justify-content: center; gap: 20px; text-align: left; }
        .header img { max-height: 80px; width: auto; filter: drop-shadow(0px 4px 8px rgba(0,0,0,0.15)); margin: 0; }
        .header-text-container { text-align: left; }
        .header h1 { margin: 0; font-family: \'Cinzel\', serif; font-size: 24px; font-weight: 600; color: #ffffff; letter-spacing: 1px; text-transform: uppercase; line-height: 1.2; }
        .header p { color: #b89755; margin: 5px 0 0 0; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600; }
        .content { padding: 40px 35px; }
        .status-badge { display: inline-block; padding: 6px 14px; background-color: #fff9db; border: 1px solid #ffe066; color: #8a6d3b; font-weight: 600; font-size: 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; }
        .intro-title { font-family: \'Cinzel\', serif; font-size: 20px; font-weight: 600; color: #2e1d0f; margin-top: 0; margin-bottom: 10px; }
        .intro-p { font-size: 15px; color: #6c757d; line-height: 1.6; margin-bottom: 30px; }
        .order-meta { display: table; width: 100%; margin-bottom: 30px; border-bottom: 1px solid #eef2f6; padding-bottom: 25px; }
        .meta-col { display: table-cell; width: 50%; vertical-align: top; }
        .meta-label { color: #adb5bd; font-weight: 700; margin-bottom: 6px; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .meta-val { color: #2e1d0f; font-size: 15px; font-weight: 600; }
        .table-container { border: 1px solid #eef2f6; border-radius: 8px; overflow: hidden; margin: 25px 0; }
        .table { width: 100%; border-collapse: collapse; margin: 0; }
        .table th { text-align: left; padding: 14px 16px; background-color: #f8fafc; border-bottom: 1px solid #eef2f6; color: #868e96; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; }
        .total-row { font-weight: bold; background-color: #fffaf0; }
        .total-label { font-family: \'Inter\', sans-serif; font-size: 14px; color: #2e1d0f; font-weight: 700; }
        .total-value { font-family: \'Inter\', sans-serif; font-size: 18px; color: #2e1d0f; font-weight: 700; }
        .footer { background: #f8fafc; text-align: center; padding: 30px; font-size: 12px; color: #868e96; border-top: 1px solid #eef2f6; line-height: 1.6; }
        .footer strong { color: #495057; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <img src="' . htmlspecialchars($logoUrl) . '" alt="DIMUTHU BAKEHOUSE">
                <div class="header-text-container">
                    <h1>Dimuthu Bake House (Pvt) Ltd.</h1>
                    <p>Artisanal Excellence</p>
                </div>
            </div>
        </div>
        <div class="content">
            <span class="status-badge">Pending Approval</span>
            <h2 class="intro-title">New Payment Recorded</h2>
            <p class="intro-p">A new agent payment has been successfully recorded and is now awaiting verification. Please review the details below.</p>
            
            <div class="order-meta">
                <div class="meta-col">
                    <div class="meta-label">Agent Name</div>
                    <div class="meta-val">' . htmlspecialchars($agentName) . '</div>
                </div>
                <div class="meta-col" style="padding-left: 20px;">
                    <div class="meta-label">Payment Method</div>
                    <div class="meta-val">' . htmlspecialchars($paymentMethodText) . '</div>
                </div>
            </div>

            <div class="order-meta" style="border-bottom: none; padding-bottom: 0; margin-bottom: 10px;">
                <div class="meta-col">
                    <div class="meta-label">Receipt Reference</div>
                    <div class="meta-val" style="color: #b89755; font-family: monospace; font-size: 16px;">' . htmlspecialchars($receiptNumber) . '</div>
                </div>
                <div class="meta-col" style="padding-left: 20px;">
                    <div class="meta-label">Submission Date</div>
                    <div class="meta-val">' . now()->format('F d, Y - h:i A') . '</div>
                </div>
            </div>

            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="padding: 14px 16px;">Order Request</th>
                            <th style="padding: 14px 16px; text-align: right;">Order Total</th>
                            <th style="padding: 14px 16px; text-align: right;">Allocated Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        ' . $distributionsHtml . '
                        <tr class="total-row">
                            <td colspan="2" class="total-label" style="padding: 18px 16px; border-top: 2px solid #eef2f6; text-align: right;">Total Amount:</td>
                            <td class="total-value" style="padding: 18px 16px; border-top: 2px solid #eef2f6; text-align: right;">Rs. ' . number_format($agentPayment->amount, 2) . '</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            ' . $notesSection . '

            <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
                <a href="' . url('/agent-payments?search=' . urlencode($receiptNumber)) . '" target="_blank" style="display: inline-block; padding: 14px 28px; background: linear-gradient(135deg, #b89755, #8a6d3b); color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; border-radius: 8px; box-shadow: 0 4px 12px rgba(184, 151, 85, 0.25); text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s ease;">View Payment Request</a>
            </div>
        </div>
        <div class="footer">
            &copy; ' . date('Y') . ' <strong>Dimuthu Bakehouse (Pvt) Ltd.</strong><br>
            527, Thewatta Road, Ragama, Sri Lanka.<br>
            <span style="font-size: 11px; margin-top: 10px; display: block; color: #adb5bd;">This is an automated notification. Please do not reply directly to this email.</span>
        </div>
    </div>
</body>
</html>';

                foreach ($recipients as $recipient) {
                    \App\Models\EmEmailSend::create([
                        'email_address' => $recipient->email_address,
                        'process_id' => 1,
                        'email_subject' => 'New Agent Payment Submission ' . $receiptNumber,
                        'email_content' => $emailContent,
                        'status' => 0, // Pending
                        'created_by' => auth()->id(),
                        'created_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $ex) {
            Log::error('Agent Payment Notification Email Queueing Failed: ' . $ex->getMessage());
        }
    }
}
