<?php

namespace App\Http\Controllers;

use App\CommonVariables;
use App\Models\AdAgentBalanceHistory;
use App\Models\CmCustomer;
use App\Models\PmProductItem;
use App\Models\StmOrderRequest;
use App\Models\StmOrderRequestHasPayment;
use App\Models\StmOrderRequestHasProduct;
use App\Models\StmQuotation;
use App\Models\StmQuotationHasProduct;
use App\Models\StmStock;
use App\Models\AdAgent;
use App\Models\AdAgentPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\StmOrderRequestHistory;
use App\Models\StmStockTransfer;
// use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DistributorAndSalesManagementController extends Controller
{
    // protected $notificationService;

    // public function __construct(NotificationService $notificationService)
    // {
    //     $this->notificationService = $notificationService;
    // }
    
    // Sales Overview
    public function salesOverviewIndex()
    {
        return view('DistributorAndSalesManagement.salesOverview');
    }

    // Quotation Management
    public function quotationManageIndex(Request $request)
    {
        $query = StmQuotation::with(['customer', 'products.productItem', 'creator']);

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('quotation_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter View
        if ($request->has('view') && $request->view != 'all') {
            $query->where('status', $request->view);
        }

        // Sort
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'value':
                    $query->orderBy('grand_total', 'desc');
                    break;
                // case 'customer': $query->orderBy('customer.name'); break; // Needs join for strict sort
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $quotations = $query->paginate(10);

        // Summary Stats
        $allQuotations = StmQuotation::all();
        $summary = [
            'totalQuotations' => $allQuotations->count(),
            'totalValue' => $allQuotations->sum('grand_total'),
            'sentCount' => $allQuotations->whereIn('status', [CommonVariables::$quotationStatusSent, CommonVariables::$quotationStatusPendingApproval])->count(),
            'pendingValue' => $allQuotations->whereIn('status', [CommonVariables::$quotationStatusSent, CommonVariables::$quotationStatusPendingApproval])->sum('grand_total'),
            'acceptedCount' => $allQuotations->where('status', CommonVariables::$quotationStatusCustomerAccepted)->count(),
            'acceptedValue' => $allQuotations->where('status', CommonVariables::$quotationStatusCustomerAccepted)->sum('grand_total'),
            'conversionRate' => $allQuotations->count() > 0 ? round(($allQuotations->where('status', CommonVariables::$quotationStatusCustomerAccepted)->count() / $allQuotations->count()) * 100) : 0,
            'expiringThisWeek' => $allQuotations->filter(function ($q) {
                return $q->valid_until && \Carbon\Carbon::parse($q->valid_until)->diffInDays(now()) < 7 && \Carbon\Carbon::parse($q->valid_until)->isFuture();
            })->count(),
            'counts' => $allQuotations->groupBy('status')->map->count(),
        ];

        // Pass viewMode for UI
        $viewMode = $request->get('view', 'all');

        return view('DistributorAndSalesManagement.quotationManagement', compact('quotations', 'summary', 'viewMode'));
    }

    public function quotationManageStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'valid_until' => 'nullable|date',
            'status' => 'required|integer',
            'grand_total' => 'nullable', // calculated backend side
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.product_item_id' => 'required|exists:pm_product_item,id',
            'products.*.quantity' => 'required|numeric|min:0.001',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Generate Quotation Number
            $date = date('Ymd');
            $prefix = 'QT';
            $last = StmQuotation::where('quotation_number', 'LIKE', "{$prefix}-{$date}-%")
                ->orderBy('quotation_number', 'desc')->first();

            $num = 1;
            if ($last) {
                // Split by - and get last part
                $parts = explode('-', $last->quotation_number);
                $num = intval(end($parts)) + 1;
            }
            $quotationNumber = "{$prefix}-{$date}-" . str_pad($num, 4, '0', STR_PAD_LEFT);

            $grandTotal = collect($request->products)->sum(function ($p) {
                return $p['quantity'] * $p['unit_price'];
            });

            $quotation = StmQuotation::create([
                'quotation_number' => $quotationNumber,
                'customer_id' => $request->customer_id,
                'quotation_date' => now(),
                'valid_until' => $request->valid_until,
                'status' => $request->status,
                'grand_total' => $grandTotal,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            foreach ($request->products as $p) {
                StmQuotationHasProduct::create([
                    'stm_quotation_id' => $quotation->id,
                    'pm_product_item_id' => $p['product_item_id'],
                    'quantity' => $p['quantity'],
                    'unit_price' => $p['unit_price'],
                    'subtotal' => $p['quantity'] * $p['unit_price'],
                ]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Quotation created successfully']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveQuotationSettings(Request $request)
    {
        try {
            $data = $request->except(['logo', '_token']);

            // Handle Logo Upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = 'company_logo.' . $file->getClientOriginalExtension();
                // Store in public/settings
                $file->storeAs('settings', $filename, 'public');
                $data['logo_path'] = 'storage/settings/' . $filename;
            }

            // Define path
            $storagePath = storage_path('app/Settings');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $filePath = $storagePath . '/quotation-settings.json';

            $currentSettings = [];
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $currentSettings = json_decode($content, true) ?? [];
            }

            $newSettings = array_merge($currentSettings, $data);

            file_put_contents($filePath, json_encode($newSettings, JSON_PRETTY_PRINT));

            return response()->json(['success' => true, 'message' => 'Settings saved successfully']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getQuotationSettings()
    {
        try {
            $filePath = storage_path('app/Settings/quotation-settings.json');

            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $settings = json_decode($content, true);

                // Add public URL for logo if exists
                if (isset($settings['logo_path'])) {
                    $settings['logo_url'] = asset($settings['logo_path']);
                }

                return response()->json(['success' => true, 'data' => $settings]);
            }

            return response()->json(['success' => true, 'data' => []]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function downloadQuotationPdf($id)
    {
        try {
            $quotation = StmQuotation::with(['customer', 'products.productItem', 'creator'])->findOrFail($id);

            // Load Settings
            $filePath = storage_path('app/Settings/quotation-settings.json');
            $settings = [];
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
                $settings = json_decode($content, true) ?? [];
                // Process logo URL for PDF (needs absolute path or base64 usually, but DomPDF handles public paths if configured)
                // For DomPDF, it's safer to use the base_path if it's local
                if (isset($settings['logo_path'])) {
                    // Calculate absolute path for DomPDF
                    $settings['logo_absolute_path'] = public_path($settings['logo_path']);
                    $settings['logo_url'] = asset($settings['logo_path']);
                }
            }

            $pdf = Pdf::loadView('DistributorAndSalesManagement.pdf.quotation', compact('quotation', 'settings'));

            return $pdf->download('Quotation-' . $quotation->quotation_number . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Error generating PDF: ' . $e->getMessage());
        }
    }

    // Order Management
    public function orderManageIndex()
    {
        // Fetch all branches for the create modal
        $branches = \App\Models\UmBranch::select('id', 'name', 'code')->where('status', 1)->get()->map(function ($b) {
            return ['id' => $b->id, 'name' => $b->name, 'code' => $b->code];
        });

        // Fetch all orders with related data
        $orders = StmOrderRequest::with(['customer', 'orderProducts.productItem', 'agent', 'history.user', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) use ($branches) {
                // Map integer status to string slug for UI compatibility
                $statusSlug = match ($order->status) {
                    0 => 'pending-approval', // CommonVariables::$orderRequestPendingApproval
                    1 => 'approved',
                    2 => 'rejected',
                    3 => 'in-production',
                    4 => 'ready-for-pickup',
                    5 => 'out-for-delivery', // dispatch-completed?
                    6 => 'dispatch-confirmed',
                    7 => 'completed',
                    default => 'pending-approval'
                };

                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'order_type' => $order->order_type,
                    'order_type_text' => match ($order->order_type) {
                        CommonVariables::$orderTypePosPickup => 'POS Pickup',
                        CommonVariables::$orderTypeSpecialOrder => 'Special Order',
                        CommonVariables::$orderTypeScheduledProduction => 'Scheduled Production',
                        default => CommonVariables::$orderTypeAgentOrder == $order->order_type ? 'Agent Order' : 'Unknown'
                    },
                    'customer_name' => $order->agent ? $order->agent->agent_name : 'Walk-in Customer',
                    'customer_phone' => $order->agent ? $order->agent->phone : '-',
                    'agent_info' => $order->agent ? [
                        'name' => $order->agent->agent_name,
                        'phone' => $order->agent->phone,
                        'code' => $order->agent->agent_code,
                        'email' => $order->agent->email,
                        'nic' => $order->agent->nic_number,
                        'address' => $order->agent->address,
                        'balance' => number_format($order->agent->outstanding_balance, 2),
                        'type' => match ($order->agent->agent_type) {
                            1 => 'Salaried',
                            2 => 'Commission Only',
                            3 => 'Credit Based',
                            default => 'General'
                        }
                    ] : null,
                    'delivery_type' => $order->delivery_type,
                    'delivery_type_text' => $order->delivery_type == CommonVariables::$deliveryTypePickup ? 'Pickup' : 'Delivery',
                    'delivery_date' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d H:i') : '-',
                    'event_type' => $order->event_type,
                    'event_type_text' => match ($order->event_type) {
                        CommonVariables::$eventTypeWedding => 'Wedding',
                        CommonVariables::$eventTypeBirthday => 'Birthday',
                        CommonVariables::$eventTypeCorporate => 'Corporate',
                        default => '-'
                    },
                    'guest_count' => $order->guest_count ?? '-',
                    'payment_details' => $order->payment_details,
                    'payment_method_text' => match ($order->payment_details) {
                        CommonVariables::$paymentMethodCash => 'Cash',
                        CommonVariables::$paymentMethodCard => 'Card',
                        CommonVariables::$paymentMethodBankTransfer => 'Bank Transfer',
                        default => '-'
                    },
                    'recurrence_pattern' => $order->recurrence_pattern,
                    'recurrence_text' => match ($order->recurrence_pattern) {
                        CommonVariables::$recurrencePatternDaily => 'Daily',
                        CommonVariables::$recurrencePatternWeekly => 'Weekly',
                        CommonVariables::$recurrencePatternMonthly => 'Monthly',
                        default => '-'
                    },
                    'end_date' => $order->end_date ? \Carbon\Carbon::parse($order->end_date)->format('Y-m-d') : '-',
                    'grand_total' => number_format((float) $order->grand_total, 2),
                    'status' => $statusSlug,
                    'status_int' => $order->status,
                    'notes' => $order->notes ?? '',
                    'created_at' => \Carbon\Carbon::parse($order->created_at)->format('Y-m-d H:i'),
                    'products' => $order->orderProducts->map(function ($op) {
                        return [
                            'product_item_id' => $op->pm_product_item_id,
                            'name' => $op->productItem->product_name ?? 'Unknown Product',
                            'quantity' => $op->quantity,
                            'dispatched_quantity' => $op->dispatched_quantity,
                            'unit_price' => number_format($op->unit_price, 2),
                            'subtotal' => number_format($op->subtotal, 2),
                        ];
                    }),
                    // Mapped properties for View Modal (CamelCase)
                    'grandTotal' => (float) $order->grand_total,
                    'amountPaid' => (float) ($order->paid_amount ?? 0),
                    'paymentReference' => $order->payment_reference,
                    // effective payment status calculation
                    'paymentStatus' => ((float) ($order->paid_amount ?? 0) >= (float) $order->grand_total) ? 'paid' : (((float) ($order->paid_amount ?? 0) > 0) ? 'partial' : 'unpaid'),
                    'channel' => match ($order->order_type) {
                        CommonVariables::$orderTypePosPickup => 'pos-pickup',
                        CommonVariables::$orderTypeSpecialOrder => 'special-order',
                        CommonVariables::$orderTypeScheduledProduction => 'scheduled-production',
                        CommonVariables::$orderTypeAgentOrder => 'agent-order',
                        default => 'pos-pickup'
                    },
                    'request_branch_name' => $branches->firstWhere('id', $order->branch_id)['name'] ?? 'Unknown Branch',
                    'req_from_branch_name' => $order->req_from_branch_id ? ($branches->firstWhere('id', $order->req_from_branch_id)['name'] ?? 'Unknown Branch') : 'Warehouse',
                    'um_branch_id' => $order->branch_id,
                    'req_from_branch_id' => $order->req_from_branch_id,
                    'outletCode' => $order->agent ? $order->agent->agent_code : '',
                    'priority' => 'normal', // TODO: Add priority to DB if needed
                    'deliveryMethod' => $order->delivery_type == CommonVariables::$deliveryTypePickup ? 'pickup' : 'delivery',
                    'pickupDate' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('Y-m-d') : '-',
                    'pickupTime' => $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('H:i') : '-',
                    'isRecurring' => !empty($order->recurrence_pattern),
                    'instanceNumber' => 1, // Placeholder
                    'productionDeadline' => $order->end_date ? \Carbon\Carbon::parse($order->end_date)->format('Y-m-d') : '-',
                    // Audit Trail (History)
                    'history' => $order->history->sortBy('created_at')->values()->map(function ($h) {
                        return [
                            'action' => $h->action,
                            'description' => $h->description,
                            'status' => $h->status,
                            'user_name' => $h->user ? $h->user->name : 'System',
                            'created_at' => \Carbon\Carbon::parse($h->created_at)->format('Y-m-d H:i'),
                        ];
                    }),
                    // Payment Details
                    'payment_records' => $order->payments->map(function ($p) {
                        return [
                            'id' => $p->id,
                            'amount' => number_format((float) $p->payment_amount, 2),
                            'method' => ucfirst(str_replace('_', ' ', $p->payment_method)),
                            'reference' => $p->payment_reference ?? '-',
                            'date' => \Carbon\Carbon::parse($p->payment_date)->format('Y-m-d H:i'),
                            'status' => $p->status == 1 ? 'Pending' : ($p->status == 2 ? 'Active' : 'Cancelled'),
                            'status_raw' => $p->status,
                            'notes' => $p->notes ?? '',
                        ];
                    }),
                ];
            });

        return view('DistributorAndSalesManagement.orderManagement', compact('orders', 'branches'));
    }

    /**
     * Search products with stock pricing (autocomplete)
     */
    public function orderManageSearchProducts(Request $request)
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = PmProductItem::select(
            'pm_product_item.id',
            'pm_product_item.product_name',
            'pm_product_item.reference_number',
            'pm_product_item.bin_code',
            DB::raw('COALESCE(MAX(stm_stock.selling_price), pm_product_item.selling_price, 0) as price')
        )
            ->leftJoin('stm_stock', 'pm_product_item.id', '=', 'stm_stock.pm_product_item_id')
            ->where(function ($q) use ($query) {
                $q->where('pm_product_item.product_name', 'LIKE', "%{$query}%")
                    ->orWhere('pm_product_item.reference_number', 'LIKE', "%{$query}%")
                    ->orWhere('pm_product_item.bin_code', 'LIKE', "%{$query}%");
            })
            ->where('pm_product_item.status', 1)
            ->groupBy(
                'pm_product_item.id',
                'pm_product_item.product_name',
                'pm_product_item.reference_number',
                'pm_product_item.bin_code',
                'pm_product_item.selling_price'
            )
            ->limit(20)
            ->get();

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'text' => $product->product_name . ' (' . ($product->reference_number ?? $product->bin_code) . ')',
                'product_name' => $product->product_name,
                'reference_number' => $product->reference_number ?? $product->bin_code,
                'price' => number_format($product->price, 2),
                'price_raw' => $product->price,
            ];
        }));
    }

    /**
     * Search customers (autocomplete)
     */
    public function orderManageSearchCustomers(Request $request)
    {
        $query = $request->input('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = CmCustomer::where(function ($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('phone', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        })
            ->limit(20)
            ->get();

        return response()->json($customers->map(function ($customer) {
            return [
                'id' => $customer->id,
                'text' => $customer->name . ($customer->phone ? ' - ' . $customer->phone : ''),
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'address' => $customer->address,
            ];
        }));
    }

    /**
     * Search quotations (autocomplete)
     */
    public function orderManageSearchQuotations(Request $request)
    {
        $query = $request->input('query', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $quotations = StmQuotation::with(['customer', 'products.productItem'])
            ->whereIn('status', [
                CommonVariables::$quotationStatusCustomerAccepted,
                CommonVariables::$quotationStatusSent,
                CommonVariables::$quotationStatusApproved,
            ])
            ->where(function ($q) use ($query) {
                $q->where('quotation_number', 'LIKE', "%{$query}%")
                    ->orWhereHas('customer', function ($cq) use ($query) {
                        $cq->where('name', 'LIKE', "%{$query}%");
                    });
            })
            ->limit(10)
            ->get();

        return response()->json($quotations->map(function ($q) {
            return [
                'id' => $q->id,
                'text' => $q->quotation_number . ' - ' . ($q->customer->name ?? 'Unknown'),
                'customer' => $q->customer ? [
                    'id' => $q->customer->id,
                    'name' => $q->customer->name,
                    'phone' => $q->customer->phone,
                    'address' => $q->customer->address,
                    'email' => $q->customer->email,
                    'type' => $q->customer->type,
                    'text' => $q->customer->name . ' - ' . $q->customer->phone,
                ] : null,
                'products' => $q->products->map(function ($p) {
                    return [
                        'product_item_id' => $p->pm_product_item_id,
                        'product_name' => $p->productItem->product->product_name ?? 'Item #' . $p->pm_product_item_id,
                        'price' => $p->unit_price,
                        'quantity' => $p->quantity,
                        'unit_price' => $p->unit_price,
                        'id' => $p->pm_product_item_id,
                        'name' => $p->productItem->product->product_name ?? 'Item #' . $p->pm_product_item_id,
                    ];
                }),
            ];
        }));
    }

    /**
     * Create new customer
     */
    public function orderManageCreateCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Check if customer exists by phone number
        $existingCustomer = CmCustomer::where('phone', $request->phone)->first();
        if ($existingCustomer) {
            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $existingCustomer->id,
                    'text' => $existingCustomer->name . ($existingCustomer->phone ? ' - ' . $existingCustomer->phone : ''),
                    'name' => $existingCustomer->name,
                    'phone' => $existingCustomer->phone,
                    'email' => $existingCustomer->email,
                    'address' => $existingCustomer->address,
                ],
            ]);
        }

        $customer = CmCustomer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'text' => $customer->name . ($customer->phone ? ' - ' . $customer->phone : ''),
                'name' => $customer->name,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'address' => $customer->address,
            ],
        ]);
    }

    /**
     * Store new order
     */
    public function orderManageStore(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Order Store Request:', $request->all());

        // Validation rules
        $rules = [
            'order_type' => 'required|integer|in:' . CommonVariables::$orderTypePosPickup . ',' . CommonVariables::$orderTypeSpecialOrder . ',' . CommonVariables::$orderTypeScheduledProduction,
            'delivery_type' => 'nullable|integer|in:' . CommonVariables::$deliveryTypePickup . ',' . CommonVariables::$deliveryTypeDelivery,
            'delivery_date' => 'required|date',
            // Allow integer ID or -1 for Warehouse
            'branch_id' => 'required|integer',
            'products' => 'required|array|min:1',
            'products.*.product_item_id' => 'required|exists:pm_product_item,id',
            'products.*.quantity' => 'required|numeric|min:0.001',
            'products.*.unit_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'quotation_id' => 'nullable|exists:stm_quotation,id',
        ];

        // Conditional validation based on order type
        if ($request->order_type == CommonVariables::$orderTypeSpecialOrder) {
            $rules['customer_id'] = 'required|exists:cm_customer,id';
            $rules['payment_details'] = 'nullable|integer|in:' . CommonVariables::$paymentMethodCash . ',' . CommonVariables::$paymentMethodCard . ',' . CommonVariables::$paymentMethodBankTransfer;
            $rules['event_type'] = 'nullable|integer|in:' . CommonVariables::$eventTypeWedding . ',' . CommonVariables::$eventTypeBirthday . ',' . CommonVariables::$eventTypeCorporate;
            $rules['guest_count'] = 'nullable|integer|min:1';
            $rules['payment_reference'] = 'nullable|string';
            $rules['paid_amount'] = 'nullable|numeric|min:0';
        }

        if ($request->order_type == CommonVariables::$orderTypeScheduledProduction) {
            // recurrence validation
            if ($request->filled('recurrence_pattern')) {
                $rules['recurrence_pattern'] = 'nullable|integer|in:' . CommonVariables::$recurrencePatternDaily . ',' . CommonVariables::$recurrencePatternWeekly . ',' . CommonVariables::$recurrencePatternMonthly;
                $rules['end_date'] = 'required_with:recurrence_pattern|date|after:delivery_date';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Determine Source Type
            // -1 indicates Warehouse, any other positive integer is a branch ID
            $isWarehouseInfo = (int) $request->branch_id === -1;
            $sourceBranchId = $isWarehouseInfo ? null : $request->branch_id;

            // 1. Calculate all order dates based on recurrence
            $orderDates = [];
            $startDate = \Carbon\Carbon::parse($request->delivery_date);
            // end_date might be just date, preserve time from start date if needed, or assume end of day.
            // Actually end_date in DB is date.
            $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : null;
            $recurrence = $request->recurrence_pattern;

            if ($request->order_type == CommonVariables::$orderTypeScheduledProduction && $recurrence && $endDate) {
                if ($recurrence == CommonVariables::$recurrencePatternDaily) {
                    $current = $startDate->copy();
                    while ($current->lte($endDate)) {
                        $orderDates[] = $current->copy();
                        $current->addDay();
                    }
                } elseif ($recurrence == CommonVariables::$recurrencePatternWeekly) {
                    $current = $startDate->copy();
                    while ($current->lte($endDate)) {
                        $orderDates[] = $current->copy();
                        $current->addWeek();
                    }
                } elseif ($recurrence == CommonVariables::$recurrencePatternMonthly) {
                    // Use addMonthsNoOverflow to handle 31st -> 28th/30th transitions correctly
                    $monthsToAdd = 0;
                    while (true) {
                        $nextDate = $startDate->copy()->addMonthsNoOverflow($monthsToAdd);
                        if ($nextDate->gt($endDate)) {
                            break;
                        }
                        $orderDates[] = $nextDate;
                        $monthsToAdd++;
                    }
                }
            } else {
                // Single order
                $orderDates[] = $startDate;
            }

            // 2. Prepare for Sequence Generation
            $prefix = match ((int) $request->order_type) {
                1 => 'POS',
                2 => 'SPO',
                3 => 'SCH',
                default => 'ORD',
            };
            $dateStr = date('Ymd');

            // Get the last sequence number for today ONCE
            $lastOrder = StmOrderRequest::where('order_number', 'LIKE', "{$prefix}-{$dateStr}-%")
                ->orderBy('order_number', 'desc')
                ->lockForUpdate() // Lock to prevent duplicates
                ->first();

            $currentSequence = $lastOrder ? intval(substr($lastOrder->order_number, -4)) : 0;

            $createdOrders = []; // To store created orders

            // Get Auth User Branch
            // Fallback for demo if users logic not fully set:
            $authBranchId = auth()->user()->current_branch_id ?? 1;

            // 3. Loop and Create Orders
            foreach ($orderDates as $deliveryDate) {
                // Increment sequence for each order
                $currentSequence++;
                $newNumber = str_pad($currentSequence, 4, '0', STR_PAD_LEFT);
                $orderNumber = "{$prefix}-{$dateStr}-{$newNumber}";

                // Calculate grand total (same for all)
                $grandTotal = collect($request->products)->sum(function ($product) {
                    return $product['quantity'] * $product['unit_price'];
                });

                // Create Order Record
                $order = StmOrderRequest::create([
                    'order_number' => $orderNumber,
                    'branch_id' => $authBranchId, // AUTH USER BRANCH (Place where order is taken)
                    'req_from_branch_id' => $sourceBranchId, // SELECTED OUTLET (Source of Stock). Null if Warehouse.
                    'customer_id' => $request->order_type == CommonVariables::$orderTypeSpecialOrder ? $request->customer_id : null,
                    'order_type' => $request->order_type,
                    'event_type' => $request->order_type == CommonVariables::$orderTypeSpecialOrder ? $request->event_type : null,
                    'guest_count' => $request->order_type == CommonVariables::$orderTypeSpecialOrder ? $request->guest_count : null,
                    'delivery_type' => $request->delivery_type,
                    'delivery_date' => $deliveryDate, // Individual date
                    'end_time' => null,
                    'recurrence_pattern' => $request->recurrence_pattern,
                    'end_date' => $request->end_date, // Keep the overall end date for reference
                    'payment_details' => $request->order_type == CommonVariables::$orderTypeSpecialOrder ? $request->payment_details : null,
                    'payment_reference' => $request->order_type == CommonVariables::$orderTypeSpecialOrder ? $request->payment_reference : null,
                    'paid_amount' => $request->order_type == CommonVariables::$orderTypeSpecialOrder ? ($request->paid_amount ?? 0) : 0,
                    'status' => CommonVariables::$orderRequestPendingApproval,
                    'grand_total' => $grandTotal,
                    'notes' => $request->notes,
                    'created_by' => auth()->id(),
                    'quotation_id' => $request->quotation_id,
                ]);

                // Attach Products and Create Stock Transfers
                foreach ($request->products as $product) {
                    $subtotal = $product['quantity'] * $product['unit_price'];

                    StmOrderRequestHasProduct::create([
                        'stm_order_request_id' => $order->id,
                        'pm_product_item_id' => $product['product_item_id'],
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'subtotal' => $subtotal,
                    ]);

                    // Create Stock Transfer record (Requesting stage)
                    StmStockTransfer::create([
                        'stm_order_request_id' => $order->id,
                        'pm_product_item_id' => $product['product_item_id'],
                        'requesting_quantity' => $product['quantity'],
                        'qty_in_unit' => $product['quantity'],
                        'batch_number' => '',
                        'stm_stock_id' => 0,
                    ]);
                }

                // Log History
                StmOrderRequestHistory::create([
                    'order_request_id' => $order->id,
                    'action' => 'Order Created',
                    'status' => $order->status,
                    'description' => 'Order request created and pending approval.',
                    'created_by' => auth()->id(),
                ]);

                $createdOrders[] = $order;
            }

            DB::commit();

            // Return success with the first order loaded
            return response()->json([
                'success' => true,
                'message' => count($createdOrders) . ' Order(s) created successfully',
                'order' => $createdOrders[0]->load(['customer', 'orderProducts.productItem']),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Approve the order request and transition to Approved status.
     * This makes it available for production planning.
     */
    public function approveOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:stm_order_requests,id',
        ]);

        try {
            DB::beginTransaction();

            $order = StmOrderRequest::with(['orderProducts', 'agent'])->findOrFail($request->order_id);

            if ($order->status != CommonVariables::$orderRequestPendingApproval) {
                return response()->json(['success' => false, 'message' => 'Order is not in a pending state.'], 400);
            }

            // Update product quantities if modified during approval
            if ($request->has('items')) {
                foreach ($request->items as $item) {
                    $product = $order->orderProducts
                        ->where('pm_product_item_id', $item['product_item_id'])
                        ->first();
                    if ($product) {
                        $newQty = floatval($item['quantity']);
                        $product->quantity = $newQty;
                        $product->subtotal = $newQty * $product->unit_price;
                        $product->save();
                    }
                }
                $order->grand_total = $order->orderProducts()->sum('subtotal');
            }

            // Update order status to Approved
            $order->status = CommonVariables::$orderRequestApproved;
            $order->save();
            
            /*
            // Send Push Notification to Agent
            if ($order->agent && $order->agent->user_id) {
                $this->notificationService->sendPushNotification(
                    $order->agent->user_id,
                    'Order Approved',
                    "Order #{$order->order_number} has been approved."
                );
            }
            */

            // Update Stock Transfer records
            StmStockTransfer::where('stm_order_request_id', $order->id)
                ->update([
                    'approved_quantity' => DB::raw('requesting_quantity'),
                    'approved_date' => now(),
                    'approved_by' => auth()->id(),
                ]);

            // Log History
            // 4. Update Agent Balance for Credit Agents (agent_type == 3)
            if ($order->agent && $order->agent->agent_type == 3) {
                $previousBalance = $order->agent->outstanding_balance;
                $updateAmount = $order->grand_total;
                $newBalance = $previousBalance + $updateAmount;

                // Update agent balance
                $order->agent->outstanding_balance = $newBalance;
                $order->agent->save();

                // Set order as Credit
                $order->payment_completed = 3; // Credit
                $order->save();

                // Log Balance History
                AdAgentBalanceHistory::create([
                    'agent_id' => $order->agent_id,
                    'order_id' => $order->id,
                    'previous_balance' => $previousBalance,
                    'amount' => $updateAmount,
                    'new_balance' => $newBalance,
                    'type' => 'Order Approved',
                    'description' => 'Outstanding balance increased due to approved order #' . $order->order_number,
                    'created_by' => auth()->id(),
                ]);

                StmOrderRequestHistory::create([
                    'order_request_id' => $order->id,
                    'action' => 'Order Approved',
                    'status' => $order->status,
                    'description' => 'Order request approved by ' . auth()->user()->name . '.',
                    'created_by' => auth()->id(),
                ]);

            } else {
                StmOrderRequestHistory::create([
                    'order_request_id' => $order->id,
                    'action' => 'Order Approved',
                    'status' => $order->status,
                    'description' => 'Order request approved by ' . auth()->user()->name . '.',
                    'created_by' => auth()->id(),
                ]);
            }



            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Order approved successfully and moved to production planning!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to approve order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update order status
     */
    public function orderManageUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:stm_order_requests,id',
            'status' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 422);
        }

        try {
            $order = StmOrderRequest::findOrFail($request->order_id);
            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    /**
     * View Payment Approval Page
     */
    public function paymentApprovalView($id)
    {
        try {
            $payment = StmOrderRequestHasPayment::with([
                'orderRequest.agent',
                'orderRequest.orderProducts.productItem',
                'agentPayment' // New relationship
            ])->findOrFail($id);

            return view('DistributorAndSalesManagement.paymentApproval', compact('payment'));
        } catch (\Exception $e) {
            return back()->with('error', 'Payment record not found.');
        }
    }

    /**
     * Admin Approve Agent Payment
     */
    public function approvePayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:stm_order_request_has_payments,id',
        ]);

        try {
            DB::beginTransaction();

            $payment = StmOrderRequestHasPayment::with(['orderRequest.agent', 'agentPayment'])->findOrFail($request->payment_id);

            if ($payment->status != 1) {
                return response()->json(['success' => false, 'message' => 'Payment is already processed.'], 400);
            }

            // 1. Update Payment Status to Approved (2)
            $payment->status = 2; // Active/Approved
            $payment->save();

            // Update the header status as well if it's a single payment link
            if ($payment->agentPayment) {
                $payment->agentPayment->status = 1; // Approved
                $payment->agentPayment->save();
            }

            $order = $payment->orderRequest;
            if (!$order) {
                throw new \Exception('Associated order not found.');
            }

            // 2. Update Order's paid_amount
            // Recalculate based on ALL approved payments for this order
            $totalPaid = StmOrderRequestHasPayment::where('stm_order_request_id', $order->id)
                ->where('status', 2)
                ->sum('payment_amount');

            $order->paid_amount = $totalPaid;

            // Update payment_completed status
            // 0: Unpaid, 1: Partial, 2: Paid, 3: Credit
            if ($order->paid_amount >= $order->grand_total - 0.01) {
                $order->payment_completed = 2; // Fully Paid
            } else if ($order->paid_amount > 0) {
                $order->payment_completed = 1; // Partially Paid
            }
            $order->save();

            // 3. Update Agent Balance (Decrease outstanding debt)
            $agent = $order->agent;
            if ($agent) {
                $previousBalance = $agent->outstanding_balance;
                $amount = $payment->payment_amount;
                $newBalance = $previousBalance - $amount;

                $agent->outstanding_balance = $newBalance;
                $agent->total_collections = ($agent->total_collections ?? 0) + $amount;
                $agent->save();

                // 4. Log Balance History
                AdAgentBalanceHistory::create([
                    'agent_id' => $agent->id,
                    'order_id' => $order->id,
                    'previous_balance' => $previousBalance,
                    'amount' => -$amount, // Negative because it's a payment reducing balance
                    'new_balance' => $newBalance,
                    'type' => 'Payment Approved',
                    'description' => "Balance decreased due to approved payment ({$payment->payment_method}) for Order #{$order->order_number}. Payment ID: {$payment->id}",
                    'created_by' => auth()->id(),
                ]);
            }

            // 5. Log Order History
            StmOrderRequestHistory::create([
                'order_request_id' => $order->id,
                'action' => 'Payment Approved',
                'status' => $order->status,
                'description' => "Payment of Rs. " . number_format($payment->payment_amount, 2) . " approved by " . auth()->user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment approved and agent balance updated successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to approve payment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Dispatch Order - Following GRN Pattern
     */
    public function dispatchOrder(Request $request)
    {

        \Log::info('dispatchOrder initiated', ['request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:stm_order_requests,id',
            'items' => 'required|array',
            'items.*.product_item_id' => 'required|exists:pm_product_item,id',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', $validator->errors()->toArray());

            return response()->json(['success' => false, 'message' => 'Validation Error: ' . implode(', ', $validator->errors()->all())], 422);
        }

        try {
            DB::beginTransaction();
            \Log::info('Transaction started');

            $order = StmOrderRequest::with('orderProducts')->findOrFail($request->order_id);
            \Log::info('Order found', ['order_id' => $order->id, 'order_number' => $order->order_number]);

            // 1. Create GRN Record
            $grn = $this->createDispatchGrn($order);

            // 2. Get Batch Sequence Start
            $currentBatchNum = $this->getNextDispatchBatchNumber();

            // 3. Process Items
            foreach ($request->items as $item) {
                $this->processDispatchItem($item, $order, $grn, $currentBatchNum);
            }

            // 4. Recalculate grand total based on dispatched quantities
            $order->refresh();
            $order->grand_total = $order->orderProducts->sum(function ($p) {
                $qty = $p->dispatched_quantity ?? $p->quantity;
                return $qty * $p->unit_price;
            });
            // Set status AFTER refresh so it doesn't get overwritten
            $order->status = 5; // Out for Delivery
            $order->save();
            \Log::info('Order Status Updated to 5');
            
            /*
            // Send Push Notification to Agent
            if ($order->agent && $order->agent->user_id) {
                $this->notificationService->sendPushNotification(
                    $order->agent->user_id,
                    'Order Dispatched',
                    "Order #{$order->order_number} has been dispatched and is out for delivery."
                );
            }
            */

            // 5. Log History
            StmOrderRequestHistory::create([
                'order_request_id' => $order->id,
                'action' => 'Order Dispatched',
                'status' => $order->status,
                'description' => 'Order dispatched by ' . auth()->user()->name . '.',
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            \Log::info('Transaction Committed');

            return response()->json(['success' => true, 'message' => 'Order dispatched successfully']);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Dispatch Order Error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());

            return response()->json(['success' => false, 'message' => 'Error dispatching order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Approve Dispatch - Update Barcodes Branch ID
     */
    public function confirmDispatch(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:stm_order_requests,id',
        ]);

        try {
            DB::beginTransaction();

            $order = StmOrderRequest::findOrFail($request->order_id);

            // Update all barcodes for this order to destination branch

            $barcodes = \App\Models\StmBarcode::where('stm_order_requests_id', $order->id)
                ->whereNull('agent_id')
                ->get();

            foreach ($barcodes as $barcode) {
                $barcode->agent_id = $order->agent_id; // Update to destination
                $barcode->save();

                // Create History
                \App\Models\StmBarcodesHistory::create([
                    'barcode_id' => $barcode->id,
                    'created_by' => auth()->id(),
                    'action' => 'DISPATCH_APPROVED',
                    'description' => "Dispatch approved for Order #{$order->order_number} - Moved to Branch #{$order->branch_id}",
                ]);
            }

            // Get all products for this order (using orderProducts relationship)
            $orderProducts = $order->orderProducts;

            foreach ($orderProducts as $orderProduct) {
                $productId = $orderProduct->pm_product_item_id;
                $product = $orderProduct->productItem;

                // Get the stock_id from barcode table for this order and product
                $barcode = \App\Models\StmBarcode::where('stm_order_requests_id', $order->id)
                    ->where('pm_product_item_id', $productId)
                    ->first();

                $stockId = $barcode ? $barcode->stm_stock_id : null;

                // Create Branch Stock record with null branch_id and agent_id from order

                \App\Models\StmBranchStock::firstOrCreate(
                    [
                        'um_branch_id' => null,
                        'agent_id' => $order->agent_id,
                        'pm_product_item_id' => $productId,
                    ],
                    [
                        'stm_order_request_has_product_id' => $orderProduct->id,
                        'quantity' => $orderProduct->quantity,
                        'created_by' => auth()->id(),
                        'stm_stock_id' => $stockId,
                        'status' => 1,
                        'updated_by' => auth()->id(),
                    ]
                );
            }

            // Update Order Status to 'Dispatch Confirmed' (6)
            $order->status = 6;
            $order->save();

            // History Log for Order
            StmOrderRequestHistory::create([
                'order_request_id' => $order->id,
                'action' => 'Dispatch Confirmed',
                'status' => $order->status,
                'description' => 'Order delivery confirmed and barcodes moved to agent/branch.',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Dispatch confirmed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error confirming dispatch: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Complete Order - Final Settlement
     */
    public function completeOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:stm_order_requests,id',
        ]);

        try {
            DB::beginTransaction();

            $order = StmOrderRequest::findOrFail($request->order_id);

            // Update Order Status to 'Completed' (7)
            $order->status = 7;
            $order->save();

            // History Log
            StmOrderRequestHistory::create([
                'order_request_id' => $order->id,
                'action' => 'Order Completed',
                'status' => $order->status,
                'description' => 'Order has been fully completed and settled.',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Order completed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error completing order: ' . $e->getMessage()], 500);
        }
    }

    private function generateDispatchGrnNumber()
    {
        $lastGrn = \App\Models\StmGrn::latest('id')->first();
        $nextNumber = 1;
        if ($lastGrn && $lastGrn->grn_number) {
            $parts = explode('-', $lastGrn->grn_number);
            if (count($parts) > 1) {
                $nextNumber = intval(end($parts)) + 1;
            } else {
                $nextNumber = $lastGrn->id + 1;
            }
        } elseif ($lastGrn) {
            $nextNumber = $lastGrn->id + 1;
        }

        return 'GRN-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    private function getInternalSupplierId()
    {
        $supplier = \App\Models\Supplier::first();
        if ($supplier) {
            return $supplier->id;
        }

        $supplier = \App\Models\Supplier::create([
            'name' => 'Internal Dispatch',
            'status' => 1,
        ]);

        return $supplier->id;
    }

    private function createDispatchGrn(StmOrderRequest $order)
    {
        $grnNumber = $this->generateDispatchGrnNumber();
        $supplierId = $this->getInternalSupplierId();

        $grn = \App\Models\StmGrn::create([
            'supplier_id' => $supplierId,
            'purchase_order_id' => null,
            'grn_number' => $grnNumber,
            'invoice_number' => $order->order_number,
            'invoice_amount' => $order->grand_total,
            'notes' => "Dispatch GRN for Order #{$order->order_number}",
            'is_completed' => 1,
            'is_active' => 1,
            'created_by' => auth()->id(),
        ]);
        \Log::info('GRN Created', ['grn_id' => $grn->id]);

        return $grn;
    }

    private function getNextDispatchBatchNumber()
    {
        $lastStockIn = \App\Models\StmStockIn::whereNotNull('batch_number')
            ->where('batch_number', 'LIKE', 'DISP%')
            ->latest('id')
            ->first();

        if ($lastStockIn) {
            return intval(substr($lastStockIn->batch_number, 4));
        }

        return 0;
    }

    private function processDispatchItem($item, StmOrderRequest $order, $grn, int &$currentBatchNum)
    {
        $productId = $item['product_item_id'];
        $qty = floatval($item['quantity']);

        if ($qty <= 0) {
            return;
        }

        $currentBatchNum++;
        $batchNumber = 'DISP' . str_pad($currentBatchNum, 4, '0', STR_PAD_LEFT);

        $product = PmProductItem::find($productId);
        if (!$product) {
            \Log::error("Product not found: $productId");
            throw new \Exception("Product ID $productId not found");
        }

        $orderProduct = $order->orderProducts->where('pm_product_item_id', $productId)->first();

        // Create StockIn
        $stockIn = \App\Models\StmStockIn::create([
            'stm_grn_id' => $grn->id,
            'pm_product_item_id' => $productId,
            'added_quantity' => $qty,
            'costing_price' => $product->cost_price ?? 0,
            'selling_price' => $orderProduct->unit_price ?? 0,
            'notes' => "Dispatched for Order #{$order->order_number}",
            'batch_number' => $batchNumber,
            'quality_check' => CommonVariables::$passedQuality,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Create Stock
        $stock = StmStock::create([
            'stm_stock_in_id' => $stockIn->id,
            'pm_product_item_id' => $productId,
            'stock_date' => now(),
            'quantity' => 0,
            'costing_price' => $product->cost_price ?? 0,
            'selling_price' => $orderProduct->unit_price ?? 0,
            'batch_number' => $batchNumber,
            'quality_check' => CommonVariables::$passedQuality,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Generate Barcodes
        $barcodeValue = $product->ref_number_auto ?? 'NO-REF-' . time();
        for ($i = 0; $i < $qty; $i++) {
            $barcode = \App\Models\StmBarcode::create([
                'barcode' => $barcodeValue,
                'stm_stock_id' => $stock->id,
                'pm_product_item_id' => $productId,
                'qty_in_unit' => 1,
                'stm_order_requests_id' => $order->id,
                'um_branch_id' => null,
                'selling_price' => $orderProduct->unit_price ?? 0,
                'is_sold' => 0,
                'created_by' => auth()->id(),
            ]);

            \App\Models\StmBarcodesHistory::create([
                'barcode_id' => $barcode->id,
                'created_by' => auth()->id(),
                'action' => 'DISPATCHED',
                'description' => "Dispatched for Order #{$order->order_number}",
            ]);
        }

        // Update Stock Transfer
        $stockTransfer = \App\Models\StmStockTransfer::where('stm_order_request_id', $order->id)
            ->where('pm_product_item_id', $productId)
            ->first();

        if ($stockTransfer) {
            $stockTransfer->update([
                'dispatched_quantity' => $qty,
                'dispatched_date' => now(),
                'dispatched_by' => auth()->id(),
                'batch_number' => $batchNumber,
                'stm_stock_id' => $stock->id,
            ]);
        }

        // Create Branch Stock Record
        \App\Models\StmBranchStock::create([
            'pm_product_item_id' => $productId,
            'um_branch_id' => $order->branch_id,
            'stm_stock_id' => $stock->id,
            'stm_stock_transfer_id' => $stockTransfer ? $stockTransfer->id : null,
            'stm_order_request_has_product_id' => $orderProduct ? $orderProduct->id : null,
            'agent_id' => $order->agent_id,
            'quantity' => $qty,
            'status' => 0, // In-transit / Pending confirmation
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        // Update Order Product – store dispatched qty separately, preserve original quantity

        if ($orderProduct) {
            $orderProduct->dispatched_quantity = $qty;
            $orderProduct->subtotal = $qty * $orderProduct->unit_price;
            $orderProduct->save();
        }

    }

    /**
     * Agent Payment Management Index
     */
    public function agentPaymentIndex(Request $request)
    {
        $agents = AdAgent::where('status', 1)->get();
        
        $query = AdAgentPayment::with(['agent', 'distributions.orderRequest']);

        // Filter by Agent
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Filter by Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('payment_date', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);

        return view('DistributorAndSalesManagement.agentPaymentManagement', compact('payments', 'agents'));
    }

    /**
     * Get Order Details for an Agent Payment
     */
    public function getAgentPaymentOrders($id)
    {
        try {
            $payment = AdAgentPayment::with(['agent', 'distributions.orderRequest.orderProducts.productItem'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $payment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment record not found.'
            ], 404);
        }
    }

    /**
     * Bulk Approve Agent Payments
     */
    public function approveBulkAgentPayments(Request $request)
    {
        $request->validate([
            'agent_payment_id' => 'required|exists:ad_agent_payments,id',
        ]);

        try {
            DB::beginTransaction();

            $agentPayment = AdAgentPayment::with(['distributions', 'agent'])->findOrFail($request->agent_payment_id);

            if ($agentPayment->status != 0) {
                return response()->json(['success' => false, 'message' => 'This payment group is already processed or rejected.'], 400);
            }

            foreach ($agentPayment->distributions as $distribution) {
                if ($distribution->status == 1) { // If pending
                    // 1. Update Distribution Status to Approved (2)
                    $distribution->status = 2; // Active/Approved
                    $distribution->save();

                    $order = StmOrderRequest::find($distribution->stm_order_request_id);
                    if ($order) {
                        // 2. Update Order's paid_amount
                        $totalPaid = StmOrderRequestHasPayment::where('stm_order_request_id', $order->id)
                            ->where('status', 2)
                            ->sum('payment_amount');

                        $order->paid_amount = $totalPaid;

                        // Update payment_completed status
                        if ($order->paid_amount >= $order->grand_total - 0.01) {
                            $order->payment_completed = 2; // Fully Paid
                        } else if ($order->paid_amount > 0) {
                            $order->payment_completed = 1; // Partially Paid
                        }
                        $order->save();
                        
                        // 3. Update Agent Balance (Decrease outstanding debt)
                        $agent = $agentPayment->agent;
                        if ($agent) {
                            $agent->outstanding_balance -= $distribution->payment_amount;
                            $agent->total_collections += $distribution->payment_amount;
                            $agent->save();

                            // 4. Log Balance History
                            AdAgentBalanceHistory::create([
                                'agent_id' => $agent->id,
                                'type' => 2, // Collection
                                'amount' => $distribution->payment_amount,
                                'previous_balance' => $agent->outstanding_balance + $distribution->payment_amount,
                                'new_balance' => $agent->outstanding_balance,
                                'reference_id' => $distribution->id,
                                'reference_type' => 'ad_agent_payment_distribution',
                                'notes' => 'Bulk approval for distribution from Agent Payment #' . $agentPayment->id,
                                'created_by' => auth()->id()
                            ]);

                            // 5. Log Order Request History
                            StmOrderRequestHistory::create([
                                'order_request_id' => $order->id,
                                'created_by' => auth()->id(),
                                'action' => 'Payment Approved',
                                'status' => $order->status,
                                'description' => "Payment of Rs. " . number_format($distribution->payment_amount, 2) . " approved via Bulk Agent Payment #" . $agentPayment->id,
                            ]);
                        }
                    }
                }
            }

            // Mark master payment as Approved (1)
            $agentPayment->status = 1;
            $agentPayment->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'All distributions in this payment group have been approved.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk payment approval failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber($orderType)
    {
        $prefix = match ($orderType) {
            1 => 'POS',  // pos_pickup
            2 => 'SPO',  // special_order
            3 => 'SCH',  // scheduled_production
            default => 'ORD',
        };

        $date = date('Ymd');
        $lastOrder = StmOrderRequest::where('order_number', 'LIKE', "{$prefix}-{$date}-%")
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$date}-{$newNumber}";
    }

    public function invoiceManageIndex()
    {
        // 1. Create Comprehensive Dummy Data
        $dummyData = [
            // 1. PAID (Individual - Cash)
            [
                'id' => 1,
                'invoice_number' => 'INV-2024-001',
                'status' => 'paid',
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '+94 77 123 4567',
                'customer_type' => 'individual',
                'billing_address' => '123, Galle Road, Colombo 03',
                'grand_total' => 45000.00,
                'subtotal' => 40000.00,
                'tax' => 5000.00,
                'discount' => 0.00,
                'amount_due' => 0.00,
                'amount_paid' => 45000.00,
                'invoice_date' => '2024-12-01',
                'due_date' => '2024-12-15',
                'payment_terms' => 'Net 14',
                'sales_person_name' => 'Kasun Perera',
                'shipping_charges' => 0,
                'adjustments' => 0,
                'terms_and_conditions' => 'No returns after 7 days.',
                'customer_notes' => 'Deliver to back gate.',
                'internal_notes' => '',
                'quotation_id' => null,
                'order_id' => 'ORD-999',
                'created_at' => '2024-12-01 10:00:00',
                'lineItems' => [
                    (object) [
                        'id' => 'li1',
                        'productName' => 'Chocolate Cake',
                        'description' => '1kg Dark Chocolate',
                        'quantity' => 10,
                        'unit' => 'kg',
                        'unitPrice' => 4000.00,
                        'discount' => 0,
                        'tax' => 500,
                        'lineTotal' => 45000.00,
                    ],
                ],
                'payments' => [
                    (object) [
                        'id' => 1,
                        'amount' => 45000.00,
                        'paymentDate' => '2024-12-02 10:00:00',
                        'method' => 'cash',
                        'referenceNumber' => 'REC-001',
                        'notes' => 'Paid at counter',
                        'receivedBy' => 'Admin',
                    ],
                ],
            ],

            // 2. SENT (Corporate - Large Order)
            [
                'id' => 2,
                'invoice_number' => 'INV-2024-002',
                'status' => 'sent',
                'customer_name' => 'ABC Corp',
                'customer_email' => 'accounts@abccorp.com',
                'customer_phone' => '+94 11 222 3333',
                'customer_type' => 'business',
                'billing_address' => '45, Industrial Zone, Biyagama',
                'grand_total' => 125000.50,
                'subtotal' => 120000.00,
                'tax' => 4000.50,
                'discount' => 0.00,
                'amount_due' => 125000.50,
                'amount_paid' => 0.00,
                'invoice_date' => date('Y-m-d', strtotime('-5 days')),
                'due_date' => date('Y-m-d', strtotime('+25 days')),
                'payment_terms' => 'Net 30',
                'sales_person_name' => 'Amal Silva',
                'shipping_charges' => 1000,
                'adjustments' => 0,
                'terms_and_conditions' => 'Standard corporate terms apply. Interest charged on overdue accounts.',
                'customer_notes' => '',
                'internal_notes' => 'Follow up next week.',
                'quotation_id' => 'QT-500',
                'order_id' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'lineItems' => [
                    (object) [
                        'id' => 'li2',
                        'productName' => 'Burger Buns',
                        'description' => 'Pack of 6',
                        'quantity' => 100,
                        'unit' => 'packs',
                        'unitPrice' => 1200.00,
                        'discount' => 0,
                        'tax' => 50.00,
                        'lineTotal' => 125000.50,
                    ],
                ],
                'payments' => [],
            ],

            // 3. OVERDUE (Individual - High Priority)
            [
                'id' => 3,
                'invoice_number' => 'INV-2024-003',
                'status' => 'overdue',
                'customer_name' => 'Sarah Smith',
                'customer_email' => 'sarah@gmail.com',
                'customer_phone' => '+94 71 555 6666',
                'customer_type' => 'individual',
                'billing_address' => '89, Flower Road, Colombo 07',
                'grand_total' => 25000.00,
                'subtotal' => 23000.00,
                'tax' => 2000.00,
                'discount' => 0.00,
                'amount_due' => 25000.00,
                'amount_paid' => 0.00,
                'invoice_date' => '2024-11-01',
                'due_date' => '2024-11-15',
                'payment_terms' => 'Net 14',
                'sales_person_name' => 'Kasun Perera',
                'shipping_charges' => 0,
                'adjustments' => 0,
                'terms_and_conditions' => '',
                'customer_notes' => 'Urgent delivery requested.',
                'internal_notes' => 'Customer promised to pay by end of month. Call again on Monday.',
                'quotation_id' => null,
                'order_id' => 'ORD-888',
                'created_at' => '2024-11-01 09:00:00',
                'lineItems' => [
                    (object) [
                        'id' => 'li3',
                        'productName' => 'Wedding Cake Structure',
                        'description' => '3 Tier Dummy',
                        'quantity' => 1,
                        'unit' => 'unit',
                        'unitPrice' => 23000.00,
                        'discount' => 0,
                        'tax' => 2000,
                        'lineTotal' => 25000.00,
                    ],
                ],
                'payments' => [],
            ],

            // 4. PARTIALLY PAID (Business - Multiple Payments)
            [
                'id' => 4,
                'invoice_number' => 'INV-2024-004',
                'status' => 'partially-paid',
                'customer_name' => 'Tech Solutions Ltd',
                'customer_email' => 'finance@techsol.lk',
                'customer_phone' => '+94 76 999 8888',
                'customer_type' => 'business',
                'billing_address' => 'Level 4, World Trade Center, Colombo',
                'grand_total' => 200000.00,
                'subtotal' => 180000.00,
                'tax' => 20000.00,
                'discount' => 0.00,
                'amount_due' => 100000.00, // 50% Remaining
                'amount_paid' => 100000.00,
                'invoice_date' => date('Y-m-d', strtotime('-10 days')),
                'due_date' => date('Y-m-d', strtotime('+4 days')),
                'payment_terms' => 'Net 14',
                'sales_person_name' => 'Ravi Kumar',
                'shipping_charges' => 0,
                'adjustments' => 0,
                'terms_and_conditions' => '50% advance, 50% on completion.',
                'customer_notes' => 'Catering for Annual General Meeting.',
                'internal_notes' => '',
                'quotation_id' => 'QT-900',
                'order_id' => 'ORD-750',
                'created_at' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'lineItems' => [
                    (object) [
                        'id' => 'li4a',
                        'productName' => 'Savoury Box',
                        'description' => 'Mix of pastries',
                        'quantity' => 200,
                        'unit' => 'box',
                        'unitPrice' => 500.00,
                        'discount' => 0,
                        'tax' => 5000,
                        'lineTotal' => 105000.00,
                    ],
                    (object) [
                        'id' => 'li4b',
                        'productName' => 'Iced Coffee',
                        'description' => '300ml Bottle',
                        'quantity' => 200,
                        'unit' => 'bottle',
                        'unitPrice' => 400.00,
                        'discount' => 0,
                        'tax' => 15000,
                        'lineTotal' => 95000.00,
                    ],
                ],
                'payments' => [
                    (object) [
                        'id' => 101,
                        'amount' => 50000.00,
                        'paymentDate' => date('Y-m-d H:i:s', strtotime('-9 days')),
                        'method' => 'bank-transfer',
                        'referenceNumber' => 'TRX-998877',
                        'notes' => 'Advance Payment 1',
                        'receivedBy' => 'Accounts',
                    ],
                    (object) [
                        'id' => 102,
                        'amount' => 50000.00,
                        'paymentDate' => date('Y-m-d H:i:s', strtotime('-2 days')),
                        'method' => 'cheque',
                        'referenceNumber' => 'CHQ-005544',
                        'notes' => 'Advance Payment 2',
                        'receivedBy' => 'Accounts',
                    ],
                ],
            ],

            // 5. DRAFT (No Customer details yet)
            [
                'id' => 5,
                'invoice_number' => 'INV-2024-005',
                'status' => 'draft',
                'customer_name' => 'Michael Brown',
                'customer_email' => 'mike@brown.com',
                'customer_phone' => null,
                'customer_type' => 'individual',
                'billing_address' => '',
                'grand_total' => 1500.00,
                'subtotal' => 1500.00,
                'tax' => 0.00,
                'discount' => 0.00,
                'amount_due' => 1500.00,
                'amount_paid' => 0.00,
                'invoice_date' => date('Y-m-d'),
                'due_date' => date('Y-m-d', strtotime('+7 days')),
                'payment_terms' => 'Net 7',
                'sales_person_name' => '',
                'shipping_charges' => 0,
                'adjustments' => 0,
                'terms_and_conditions' => '',
                'customer_notes' => '',
                'internal_notes' => 'Draft created during call.',
                'quotation_id' => null,
                'order_id' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'lineItems' => [
                    (object) [
                        'id' => 'li5',
                        'productName' => 'Custom Birthday Cake',
                        'description' => 'Draft line item',
                        'quantity' => 1,
                        'unit' => 'kg',
                        'unitPrice' => 1500.00,
                        'discount' => 0,
                        'tax' => 0,
                        'lineTotal' => 1500.00,
                    ],
                ],
                'payments' => [],
            ],

            // 6. CANCELLED (Order error)
            [
                'id' => 6,
                'invoice_number' => 'INV-2024-006',
                'status' => 'cancelled',
                'customer_name' => 'Erroneous Entry',
                'customer_email' => '',
                'customer_phone' => '',
                'customer_type' => 'individual',
                'billing_address' => '',
                'grand_total' => 500.00,
                'subtotal' => 500.00,
                'tax' => 0.00,
                'discount' => 0.00,
                'amount_due' => 500.00,
                'amount_paid' => 0.00,
                'invoice_date' => '2024-11-20',
                'due_date' => '2024-11-20',
                'payment_terms' => 'Immediate',
                'sales_person_name' => 'Admin',
                'shipping_charges' => 0,
                'adjustments' => 0,
                'terms_and_conditions' => '',
                'customer_notes' => '',
                'internal_notes' => 'Created by mistake. Voided.',
                'quotation_id' => null,
                'order_id' => null,
                'created_at' => '2024-11-20 10:00:00',
                'lineItems' => [],
                'payments' => [],
            ],

            // 7. PENDING (Retail Store Credit)
            [
                'id' => 7,
                'invoice_number' => 'INV-2024-007',
                'status' => 'pending',
                'customer_name' => 'Keells Super',
                'customer_email' => 'purchasing@keells.lk',
                'customer_phone' => '+94 11 200 0000',
                'customer_type' => 'business',
                'billing_address' => 'Slave Island, Colombo 02',
                'grand_total' => 450000.00,
                'subtotal' => 420000.00,
                'tax' => 30000.00,
                'discount' => 0.00,
                'amount_due' => 450000.00,
                'amount_paid' => 0.00,
                'invoice_date' => date('Y-m-d', strtotime('-2 days')),
                'due_date' => date('Y-m-d', strtotime('+28 days')),
                'payment_terms' => 'Net 30',
                'sales_person_name' => 'Amal Silva',
                'shipping_charges' => 0,
                'adjustments' => 0,
                'terms_and_conditions' => 'Standard retail agreement terms.',
                'customer_notes' => 'PO Ref: #K-99001',
                'internal_notes' => '',
                'quotation_id' => null,
                'order_id' => 'ORD-1001',
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'lineItems' => [
                    (object) ['id' => 'li7a', 'productName' => 'Bread Loaf (White)', 'description' => 'Sliced', 'quantity' => 500, 'unit' => 'loaf', 'unitPrice' => 150.00, 'discount' => 0, 'tax' => 0, 'lineTotal' => 75000.00],
                    (object) ['id' => 'li7b', 'productName' => 'Bread Loaf (Wheat)', 'description' => 'Sliced', 'quantity' => 300, 'unit' => 'loaf', 'unitPrice' => 200.00, 'discount' => 0, 'tax' => 0, 'lineTotal' => 60000.00],
                    (object) ['id' => 'li7c', 'productName' => 'Fish Bun', 'description' => 'Standard', 'quantity' => 1000, 'unit' => 'pcs', 'unitPrice' => 100.00, 'discount' => 0, 'tax' => 0, 'lineTotal' => 100000.00],
                ],
                'payments' => [],
            ],
        ];

        // 2. Convert to Collection
        $invoices = collect($dummyData)->map(function ($item) {
            return (object) $item;
        });

        // 3. Calculate Summary
        $summary = [
            'totalValue' => $invoices->where('status', '!=', 'cancelled')->sum('grand_total'),
            'totalInvoices' => $invoices->count(),
            'paidValue' => $invoices->sum('amount_paid'),
            'unpaidValue' => $invoices->whereIn('status', ['sent', 'pending', 'partially-paid'])->sum('amount_due'),
            'overdueValue' => $invoices->where('status', 'overdue')->sum('amount_due'),
            'overdueCount' => $invoices->where('status', 'overdue')->count(),
            'averageDaysToPayment' => 14.2,
            'invoicesByStatus' => $invoices->groupBy('status')->map->count(),
        ];

        return view('DistributorAndSalesManagement.invoiceManagement', compact('invoices', 'summary'));
    }

    // Payment Tracking
    public function paymentTrackingIndex()
    {
        // --- 1. DUMMY DATA GENERATION ---

        $dummyInvoices = [
            // Scenario 1: Overdue Invoice with one partial payment
            [
                'id' => 1,
                'invoice_number' => 'INV-2024-001',
                'status' => 'overdue',
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'grand_total' => 50000.00,
                'amount_paid' => 15000.00,
                'amount_due' => 35000.00,
                'due_date' => '2024-11-01',
                'payments' => [
                    (object) [
                        'id' => 101,
                        'amount' => 15000.00,
                        'paymentDate' => '2024-10-15 14:30:00',
                        'method' => 'cash', // cash, bank-transfer, credit-card, debit-card, cheque, mobile-payment
                        'status' => 'completed', // completed, pending, failed
                        'referenceNumber' => null,
                        'receiptNumber' => 'RCP-1001',
                        'receivedBy' => 'Cashier 1',
                        'notes' => 'Partial payment made at counter',
                        'invoiceNumber' => 'INV-2024-001', // Needed for summary list
                        'customerName' => 'John Doe',       // Needed for summary list
                    ],
                ],
            ],

            // Scenario 2: Sent Invoice (No payments yet)
            [
                'id' => 2,
                'invoice_number' => 'INV-2024-002',
                'status' => 'sent',
                'customer_name' => 'ABC Corp',
                'customer_email' => 'finance@abc.com',
                'grand_total' => 100000.00,
                'amount_paid' => 0.00,
                'amount_due' => 100000.00,
                'due_date' => date('Y-m-d', strtotime('+3 days')), // Due soon
                'payments' => [],
            ],

            // Scenario 3: Fully Paid Invoice with Multiple Payment Methods
            [
                'id' => 3,
                'invoice_number' => 'INV-2024-003',
                'status' => 'paid',
                'customer_name' => 'XYZ Ltd',
                'customer_email' => 'xyz@ltd.com',
                'grand_total' => 75000.00,
                'amount_paid' => 75000.00,
                'amount_due' => 0.00,
                'due_date' => '2024-12-01',
                'payments' => [
                    (object) [
                        'id' => 201,
                        'amount' => 25000.00,
                        'paymentDate' => '2024-11-20 09:00:00',
                        'method' => 'bank-transfer',
                        'status' => 'completed',
                        'referenceNumber' => 'TRX-888999',
                        'receiptNumber' => 'RCP-2001',
                        'receivedBy' => 'Accounts Dept',
                        'notes' => 'Advance deposit',
                        'invoiceNumber' => 'INV-2024-003',
                        'customerName' => 'XYZ Ltd',
                    ],
                    (object) [
                        'id' => 202,
                        'amount' => 50000.00,
                        'paymentDate' => '2024-12-01 11:15:00',
                        'method' => 'cheque',
                        'status' => 'completed',
                        'referenceNumber' => 'CHQ-554433',
                        'receiptNumber' => 'RCP-2002',
                        'receivedBy' => 'Manager',
                        'notes' => 'Final settlement',
                        'invoiceNumber' => 'INV-2024-003',
                        'customerName' => 'XYZ Ltd',
                    ],
                ],
            ],

            // Scenario 4: Partially Paid with a Pending Mobile Payment
            [
                'id' => 4,
                'invoice_number' => 'INV-2024-004',
                'status' => 'partially-paid',
                'customer_name' => 'Tech Solutions',
                'customer_email' => 'billing@tech.lk',
                'grand_total' => 50000.00,
                'amount_paid' => 10000.00,
                'amount_due' => 40000.00,
                'due_date' => date('Y-m-d', strtotime('-2 days')),
                'payments' => [
                    (object) [
                        'id' => 301,
                        'amount' => 10000.00,
                        'paymentDate' => date('Y-m-d H:i:s'),
                        'method' => 'mobile-payment',
                        'status' => 'pending', // Pending status example
                        'referenceNumber' => 'MP-777888',
                        'receiptNumber' => null,
                        'receivedBy' => 'System',
                        'notes' => 'Waiting for carrier confirmation',
                        'invoiceNumber' => 'INV-2024-004',
                        'customerName' => 'Tech Solutions',
                    ],
                ],
            ],
        ];

        // --- 2. PREPARE COLLECTION ---
        $invoices = collect($dummyInvoices)->map(function ($i) {
            return (object) $i;
        });

        // --- 3. CALCULATE SUMMARIES ---
        $totalReceivables = $invoices->sum('amount_due');
        $overdueAmount = $invoices->where('status', 'overdue')->sum('amount_due');
        $overdueCount = $invoices->where('status', 'overdue')->count();

        // Calculate Due Soon (Next 7 days)
        $today = \Carbon\Carbon::now();
        $dueSoonInvoices = $invoices->filter(function ($inv) use ($today) {
            $due = \Carbon\Carbon::parse($inv->due_date);

            return $inv->amount_due > 0 && $due->gte($today) && $due->diffInDays($today) <= 7;
        });
        $dueSoonAmount = $dueSoonInvoices->sum('amount_due');
        $dueSoonCount = $dueSoonInvoices->count();

        // Get Recent Payments List (Flattened for the side panel summary)
        $recentPayments = $invoices->pluck('payments')
            ->flatten()
            ->sortByDesc('paymentDate')
            ->take(5);

        $recentPaymentsAmount = $recentPayments->where('status', 'completed')->sum('amount');

        // Calculate Aging Buckets
        $aging = [
            '0-30' => ['amount' => 0, 'count' => 0],
            '31-60' => ['amount' => 0, 'count' => 0],
            '61-90' => ['amount' => 0, 'count' => 0],
            '90+' => ['amount' => 0, 'count' => 0],
        ];

        foreach ($invoices as $inv) {
            if ($inv->amount_due > 0) {
                $daysDiff = $today->diffInDays(\Carbon\Carbon::parse($inv->due_date), false);

                // Only count as aging if due date has passed (negative diff)
                if ($daysDiff < 0) {
                    $daysOverdue = abs($daysDiff);

                    if ($daysOverdue <= 30) {
                        $bucket = '0-30';
                    } elseif ($daysOverdue <= 60) {
                        $bucket = '31-60';
                    } elseif ($daysOverdue <= 90) {
                        $bucket = '61-90';
                    } else {
                        $bucket = '90+';
                    }

                    $aging[$bucket]['amount'] += $inv->amount_due;
                    $aging[$bucket]['count']++;
                }
            }
        }

        $summary = compact('totalReceivables', 'overdueAmount', 'overdueCount', 'dueSoonAmount', 'dueSoonCount', 'recentPayments', 'recentPaymentsAmount', 'aging');

        return view('DistributorAndSalesManagement.paymentTracking', compact('invoices', 'summary'));
    }

    // Delivery Scheduling
    public function deliverySchedulingIndex()
    {
        // --- 1. DUMMY DATA ---
        $dummyDeliveries = [
            [
                'id' => 1,
                'deliveryType' => 'customer',
                'invoiceNumber' => 'INV-2024-001',
                'customerName' => 'John Doe',
                'deliveryAddress' => '123 Galle Road, Colombo 03',
                'status' => 'pending',
                'priority' => 'high',
                'items' => 5,
                'scheduledDate' => null,
                'timeSlot' => null,
                'route' => null,
                'driver' => null,
            ],
            [
                'id' => 2,
                'deliveryType' => 'outlet-transfer',
                'outletName' => 'Nugegoda Branch',
                'deliveryAddress' => '45 High Level Rd, Nugegoda',
                'status' => 'scheduled',
                'priority' => 'normal',
                'items' => 120,
                'scheduledDate' => date('Y-m-d'), // Today
                'timeSlot' => 'morning',
                'route' => 'Route A',
                'driver' => 'Driver Mike',
            ],
            [
                'id' => 3,
                'deliveryType' => 'customer',
                'invoiceNumber' => 'INV-2024-005',
                'customerName' => 'Sarah Smith',
                'deliveryAddress' => '89 Flower Rd, Colombo 07',
                'status' => 'in-transit',
                'priority' => 'urgent',
                'items' => 2,
                'scheduledDate' => date('Y-m-d'),
                'timeSlot' => 'afternoon',
                'route' => 'Route B',
                'driver' => 'Driver Sam',
            ],
            [
                'id' => 4,
                'deliveryType' => 'adhoc',
                'customerName' => 'Special Event',
                'deliveryAddress' => 'BMICH, Colombo',
                'status' => 'delivered',
                'priority' => 'normal',
                'items' => 50,
                'scheduledDate' => date('Y-m-d'),
                'timeSlot' => 'morning',
                'route' => 'Route A',
                'driver' => 'Driver Mike',
                'deliveredAt' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'deliveryType' => 'customer',
                'invoiceNumber' => 'INV-2024-008',
                'customerName' => 'Tech Corp',
                'deliveryAddress' => 'World Trade Center, Colombo 01',
                'status' => 'scheduled',
                'priority' => 'normal',
                'items' => 15,
                'scheduledDate' => date('Y-m-d', strtotime('+1 day')),
                'timeSlot' => 'morning',
                'route' => 'Route C',
                'driver' => 'Driver Tom',
            ],
        ];

        $deliveries = collect($dummyDeliveries)->map(function ($d) {
            return (object) $d;
        });

        // --- 2. CALCULATE SUMMARY ---
        $today = date('Y-m-d');
        $summary = [
            'pending' => $deliveries->where('status', 'pending')->count(),
            'todayDeliveries' => $deliveries->where('scheduledDate', $today)->count(),
            'inTransit' => $deliveries->where('status', 'in-transit')->count(),
            'completedToday' => $deliveries->filter(function ($d) use ($today) {
                return $d->status === 'delivered' && str_starts_with($d->deliveredAt ?? '', $today);
            })->count(),
        ];

        // --- 3. PREPARE CALENDAR DATA ---
        // Group by Date -> TimeSlot
        $calendarData = [];
        foreach ($deliveries as $d) {
            if ($d->scheduledDate) {
                $slot = $d->timeSlot ?? 'morning';
                $calendarData[$d->scheduledDate][$slot][] = $d;
            }
        }

        // --- 4. PREPARE ROUTE DATA ---
        $routeData = $deliveries->groupBy(function ($d) {
            return $d->route ?? 'Unassigned';
        });

        return view('DistributorAndSalesManagement.deliveryManagement', compact('deliveries', 'summary', 'calendarData', 'routeData'));
    }

    public function customerManageIndex()
    {
        return view('DistributorAndSalesManagement.customerManagement');
    }
}
