<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\AdDailyLoad;
use App\Models\AdRoute;
use App\Models\CmCustomer;
use App\Models\StmOrderRequest;
use App\Models\HsGuideVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\AdAgentMonthlyTarget;
use App\Models\AdCubusinessHasInvoice;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdCubusinessInvoicePayments;
use App\Models\DmDriver;
use App\Models\SmSuperviser;
use App\Models\UmUser;
use App\Models\AdDailyLoadItem;
use App\Models\VmVehicle;
use App\Models\StmBranchStock;
use App\Models\StmBarcode;
use App\Models\StmBarcodesHistory;
use App\Models\PmProductItem;
use App\CommonVariables;

class AgentWebController extends Controller
{
    /**
     * Get the authenticated agent.
     */
    private function getAgent()
    {
        return AdAgent::where('user_id', Auth::id())->first();
    }

    public function dashboard()
    {
        $agent = $this->getAgent();
        $agentId = $agent ? $agent->id : null;

        $today = date('Y-m-d');
        $year = date('Y');
        $month = date('n');
        $monthStart = date('Y-m-01');

        $todaySales = 0;
        $totalCustomers = 0;
        $targetAmount = 0;
        $commissionAmount = 0;
        $achievedSales = 0;
        $progressPercentage = 0;
        $recentVisits = collect([]);

        if ($agentId) {
            // 1. Today's Sales
            $todaySales = AdCubusinessHasInvoice::where('created_at', '>=', $today)
                ->whereHas('business', function ($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->sum('net_price');

            // 2. Total Customers
            $totalCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->count();

            // 3. Monthly Stats (Target & Commission)
            $monthlyTarget = AdAgentMonthlyTarget::where('agent_id', $agentId)
                ->where('target_year', $year)
                ->where('target_month', $month)
                ->first();

            $targetAmount = $monthlyTarget ? (float) $monthlyTarget->monthly_sales_target : 0;
            $commissionAmount = $monthlyTarget ? (float) $monthlyTarget->monthly_commission : 0;

            // Calculate achieved sales for this month
            $achievedSales = AdCubusinessHasInvoice::where('created_at', '>=', $monthStart)
                ->whereHas('business', function ($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })
                ->sum('net_price');

            $progressPercentage = $targetAmount > 0 ? min(100, round(($achievedSales / $targetAmount) * 100)) : 0;

            // 4. Recent Visits (Latest 5 invoices as a proxy for visits)
            $recentVisits = AdCubusinessHasInvoice::whereHas('business', function ($q) use ($agentId) {
                $q->where('agent_id', $agentId);
            })
                ->with('business.customer')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'customer_name' => $invoice->business->business_name ?: ($invoice->business->customer->name ?? 'N/A'),
                        'amount' => (float) $invoice->net_price,
                        'time' => $invoice->created_at->format('h:i A'),
                        'date' => $invoice->created_at->format('Y-m-d'),
                        'status' => 'Order placed'
                    ];
                });
        }

        return view('AgentView.dashboard', compact(
            'agent',
            'todaySales',
            'totalCustomers',
            'targetAmount',
            'commissionAmount',
            'achievedSales',
            'progressPercentage',
            'recentVisits'
        ));
    }

    public function customers()
    {
        $agent = $this->getAgent();
        $agentId = $agent ? $agent->id : null;

        $customers = CmCustomer::whereHas('businessDetails', function ($query) use ($agentId) {
            if ($agentId) {
                $query->where('agent_id', $agentId);
            }
        })->with('businessDetails.route')->get();

        $routes = collect([]);
        if ($agentId) {
            $routes = \App\Models\AdRoute::where('agent_id', $agentId)->get();
        }

        return view('AgentView.customers', compact('agent', 'customers', 'routes'));
    }

    public function orders(Request $request)
    {
        $agent = $this->getAgent();
        $orders = [];
        $stats = [
            'total_value' => 0,
            'pending_count' => 0,
            'pending_value' => 0,
            'completed_count' => 0,
            'completed_value' => 0,
        ];

        if ($agent) {
            // Unfiltered stats
            $stats['total_value'] = StmOrderRequest::where('agent_id', $agent->id)->sum('grand_total');
            $stats['pending_count'] = StmOrderRequest::where('agent_id', $agent->id)->whereNotIn('status', [1, 2, 5, 7])->count();
            $stats['pending_value'] = StmOrderRequest::where('agent_id', $agent->id)->whereNotIn('status', [1, 2, 5, 7])->sum('grand_total');
            $stats['completed_count'] = StmOrderRequest::where('agent_id', $agent->id)->where('status', 7)->count();
            $stats['completed_value'] = StmOrderRequest::where('agent_id', $agent->id)->where('status', 7)->sum('grand_total');

            // Query construction
            $query = StmOrderRequest::where('agent_id', $agent->id);

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('id', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->filled('status') && $request->status !== 'all') {
                $status = $request->status;
                if ($status === 'pending') {
                    $query->whereNotIn('status', [1, 2, 5, 7]);
                } else {
                    $query->where('status', $status);
                }
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        }

        return view('AgentView.orders', compact('agent', 'orders', 'stats'));
    }

    public function createOrder()
    {
        return redirect()->route('agent-panel.orders', ['create' => 1]);
    }

    public function payments(Request $request)
    {
        $agent = $this->getAgent();
        $payments = collect([]);
        $totalCount = 0;
        $approvedSum = 0;
        $pendingSum = 0;

        if ($agent) {
            // Unfiltered stats for summary cards
            $totalCount = \App\Models\AdAgentPayment::where('agent_id', $agent->id)->count();
            $approvedSum = \App\Models\AdAgentPayment::where('agent_id', $agent->id)->where('status', 1)->sum('amount');
            $pendingSum = \App\Models\AdAgentPayment::where('agent_id', $agent->id)->where('status', 0)->sum('amount');

            // Query construction
            $query = \App\Models\AdAgentPayment::where('agent_id', $agent->id)
                ->with(['distributions.orderRequest', 'history.creator', 'creditNotes']);

            // Status filter
            if ($request->filled('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            // Search filter
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $cleanSearch = str_ireplace('REC-', '', $search);
                    $cleanSearch = ltrim($cleanSearch, '0');
                    if (is_numeric($cleanSearch) && $cleanSearch !== '') {
                        $q->where('id', $cleanSearch)
                          ->orWhere('notes', 'like', "%{$search}%");
                    } else {
                        $q->where('notes', 'like', "%{$search}%");
                     }
                });
            }

            $payments = $query->orderBy('payment_date', 'desc')
                ->paginate(10)
                ->withQueryString();
        }

        return view('AgentView.payments', compact('agent', 'payments', 'totalCount', 'approvedSum', 'pendingSum'));
    }

    public function dailyLoads()
    {
        $agent = $this->getAgent();
        $loads = [];
        if ($agent) {
            $loads = AdDailyLoad::where('agent_id', $agent->id)
                ->with(['route', 'supervisor', 'driver', 'vehicle'])
                ->withCount('items')
                ->orderBy('load_date', 'desc')
                ->get();
        }
        return view('AgentView.daily-loads', compact('agent', 'loads'));
    }

    public function createDailyLoadView()
    {
        $agent = $this->getAgent();
        return view('AgentView.daily-load-create', compact('agent'));
    }

    public function getCreateDailyLoadData(Request $request)
    {
        try {
            $agent = $this->getAgent();
            $agentId = $agent ? $agent->id : null;
            if (!$agentId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent profile not found.'
                ], 403);
            }

            // Routes: where is_added is false/null and agent matches
            $routes = AdRoute::where('agent_id', $agentId)
                ->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                })
                ->withCount('customers')
                ->get();

            // Supervisors: where is_added is false/null and agent matches
            $supervisors = SmSuperviser::where('agent_id', $agentId)
                ->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                })
                ->get();

            // Drivers: where is_added is false/null and agent matches
            $drivers = DmDriver::where('agent_id', $agentId)
                ->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                })
                ->get();

            // Vehicles: where is_added is false/null and agent matches
            $vehicles = VmVehicle::where('agent_id', $agentId)
                ->where(function ($q) {
                    $q->where('is_added', false)->orWhereNull('is_added');
                })
                ->get();

            // Products: with available stock > 0 for this agent
            $products = PmProductItem::whereIn('id', function ($query) use ($agentId) {
                $query->select('pm_product_item_id')
                    ->from('stm_branch_stock')
                    ->where('agent_id', $agentId)
                    ->groupBy('pm_product_item_id')
                    ->havingRaw('SUM(quantity) > 0');
            })
                ->get()
                ->map(function ($product) use ($agentId) {
                    $stockQty = StmBranchStock::where('agent_id', $agentId)
                        ->where('pm_product_item_id', $product->id)
                        ->sum('quantity');

                    return [
                        'id' => $product->id,
                        'product_name' => $product->product_name,
                        'reference_number' => $product->reference_number,
                        'selling_price' => (float) ($product->selling_price ?? 0),
                        'wholesale_price' => (float) ($product->wholesale_price ?? 0),
                        'stock_quantity' => (float) $stockQty,
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => [
                    'routes' => $routes,
                    'supervisors' => $supervisors,
                    'drivers' => $drivers,
                    'vehicles' => $vehicles,
                    'products' => $products
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch creation data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeDailyLoad(Request $request)
    {
        $agent = $this->getAgent();
        $agentId = $agent ? $agent->id : null;
        if (!$agentId) {
            return response()->json([
                'status' => false,
                'message' => 'Agent profile not found.'
            ], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'route_id' => 'required|integer',
            'supervisor_id' => 'nullable|integer',
            'driver_id' => 'nullable|integer',
            'vehicle_id' => 'nullable|integer',
            'load_date' => 'required|date',
            'starting_mileage' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.product_item_id' => 'required_with:items|integer',
            'items.*.quantity' => 'required_with:items|numeric|min:0.001',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            return DB::transaction(function () use ($request, $agentId) {
                $load = AdDailyLoad::create([
                    'agent_id' => $agentId,
                    'route_id' => $request->route_id,
                    'supervisor_id' => $request->supervisor_id,
                    'driver_id' => $request->driver_id,
                    'vehicle_id' => $request->vehicle_id,
                    'load_date' => $request->load_date,
                    'starting_mileage' => $request->starting_mileage,
                    'notes' => $request->notes,
                    'status' => 1,
                    'load_status' => 1, // 1: Loading
                    'is_mark_as_loaded' => false,
                ]);

                // Add product items if provided
                if ($request->has('items') && is_array($request->items)) {
                    foreach ($request->items as $item) {
                        $productItemId = $item['product_item_id'];
                        $requestedQty = $item['quantity'];

                        // 1. Find Branch Stock with available quantity
                        $branchStocks = StmBranchStock::where('agent_id', $agentId)
                            ->where('pm_product_item_id', $productItemId)
                            ->where('quantity', '>', 0)
                            ->get();

                        $totalAvailableQty = $branchStocks->sum('quantity');

                        if ($totalAvailableQty < $requestedQty) {
                            $productName = PmProductItem::find($productItemId)->product_name ?? 'Product';
                            throw new \Exception("Insufficient stock for $productName. Available: $totalAvailableQty");
                        }

                        $remainingQtyToDeduct = $requestedQty;
                        $branchStockIdsUsed = [];

                        // 2. Reduce Branch Stock across available records
                        foreach ($branchStocks as $stockRecord) {
                            if ($remainingQtyToDeduct <= 0) {
                                break;
                            }

                            $qtyToDeduct = min($stockRecord->quantity, $remainingQtyToDeduct);
                            $stockRecord->decrement('quantity', $qtyToDeduct);
                            $remainingQtyToDeduct -= $qtyToDeduct;
                            $branchStockIdsUsed[] = $stockRecord->id;
                        }

                        // 3. Create Daily Load Item
                        AdDailyLoadItem::create([
                            'daily_load_id' => $load->id,
                            'product_item_id' => $productItemId,
                            'stm_branch_stock_id' => $branchStockIdsUsed[0] ?? null,
                            'loaded_qty' => $requestedQty,
                            'available_quantity' => $requestedQty,
                            'price' => $item['price'],
                            'total_value' => $requestedQty * $item['price'],
                        ]);

                        // 4. Update Barcodes
                        $barcodes = StmBarcode::where('agent_id', $agentId)
                            ->where('pm_product_item_id', $productItemId)
                            ->whereNull('ad_daily_load_id')
                            ->where('is_sold', false)
                            ->limit($requestedQty)
                            ->get();

                        foreach ($barcodes as $barcode) {
                            $barcode->update(['ad_daily_load_id' => $load->id]);

                            // 5. Record Barcode History
                            StmBarcodesHistory::create([
                                'barcode_id' => $barcode->id,
                                'created_by' => auth()->id(),
                                'action' => 'DAILY_LOAD_ASSIGNED',
                                'description' => "Assigned to Daily Load #{$load->id} (Date: {$load->load_date})",
                            ]);
                        }
                    }
                }

                // Mark related resources as is_added = true
                if ($request->route_id) {
                    AdRoute::where('id', $request->route_id)->update(['is_added' => true]);
                }
                if ($request->supervisor_id) {
                    SmSuperviser::where('id', $request->supervisor_id)->update(['is_added' => true]);
                }
                if ($request->driver_id) {
                    DmDriver::where('id', $request->driver_id)->update(['is_added' => true]);
                }
                if ($request->vehicle_id) {
                    VmVehicle::where('id', $request->vehicle_id)->update(['is_added' => true]);
                }

                $load->load(['route', 'supervisor', 'driver', 'vehicle', 'items.product']);

                if ($load->supervisor_id) {
                    $supervisor = \App\Models\SmSuperviser::find($load->supervisor_id);
                    if ($supervisor && $supervisor->user_id) {
                        try {
                            app(\App\Services\NotificationService::class)->createAndSend(
                                $supervisor->user_id,
                                'New Daily Load Assigned',
                                "Daily load #{$load->id} for date {$load->load_date} has been assigned to you.",
                                'daily_load',
                                'created',
                                $load->id,
                                ['daily_load_id' => $load->id]
                            );
                        } catch (\Exception $e) {
                            // Suppress errors for notification sending if service is unavailable
                        }
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Daily load created successfully',
                    'data' => $load,
                ], 201);
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Daily Load Creation Failed: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create daily load: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showDailyLoad($id)
    {
        $agent = $this->getAgent();
        if (!$agent) {
            return redirect()->route('agent-panel.dashboard')->with('error', 'Agent profile not found.');
        }

        $load = AdDailyLoad::where('agent_id', $agent->id)
            ->with(['route', 'supervisor', 'driver', 'vehicle', 'items.product'])
            ->findOrFail($id);

        return view('AgentView.daily-load-detail', compact('agent', 'load'));
    }

    public function updateDailyLoadStatus(Request $request, $id)
    {
        $agent = $this->getAgent();
        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Agent profile not found.'], 403);
        }

        $load = AdDailyLoad::where('agent_id', $agent->id)->findOrFail($id);
        $action = $request->input('action');

        if ($action === 'mark_as_loaded') {
            $load->update([
                'is_mark_as_loaded' => true,
                'load_status' => 2 // Loaded
            ]);
        } elseif ($action === 'start_trip') {
            $request->validate([
                'starting_mileage' => 'nullable|numeric',
                'notes' => 'nullable|string'
            ]);
            $load->update([
                'starting_mileage' => $request->input('starting_mileage'),
                'notes' => $request->input('notes'),
                'load_status' => 3 // Started
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Load status updated successfully']);
    }

    public function finishDailyLoad(Request $request, $id)
    {
        $agent = $this->getAgent();
        if (!$agent) {
            return response()->json(['success' => false, 'message' => 'Agent profile not found.'], 403);
        }

        $load = AdDailyLoad::where('agent_id', $agent->id)->with('items')->findOrFail($id);

        $request->validate([
            'ending_mileage' => 'required|numeric',
            'items' => 'required|array',
            'items.*.product_item_id' => 'required|integer',
            'items.*.unload_qty' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update Load header
            $load->update([
                'ending_mileage' => $request->input('ending_mileage'),
                'unload_time' => now(),
                'load_status' => 5, // Finished
                'status' => 0       // Inactive
            ]);

            // 2. Update unload quantities
            foreach ($request->input('items') as $itemData) {
                \App\Models\AdDailyLoadItem::where('daily_load_id', $load->id)
                    ->where('product_item_id', $itemData['product_item_id'])
                    ->update(['unload_qty' => $itemData['unload_qty']]);
            }

            // 3. Re-fetch items with relationships to update stock and barcodes (simulated from completeRoute/finishDailyLoad API logic)
            $items = \App\Models\AdDailyLoadItem::where('daily_load_id', $load->id)->get();
            foreach ($items as $item) {
                if ($item->unload_qty > 0) {
                    if ($item->stm_branch_stock_id) {
                        \App\Models\StmBranchStock::where('id', $item->stm_branch_stock_id)
                            ->increment('quantity', $item->unload_qty);
                    }

                    $barcodesToRelease = \App\Models\StmBarcode::where('ad_daily_load_id', $load->id)
                        ->where('pm_product_item_id', $item->product_item_id)
                        ->where('is_sold', false)
                        ->limit((int) $item->unload_qty)
                        ->get();

                    foreach ($barcodesToRelease as $barcode) {
                        $barcode->update(['ad_daily_load_id' => null]);
                        \App\Models\StmBarcodesHistory::create([
                            'stm_barcode_id' => $barcode->id,
                            'status' => 'unloaded',
                            'notes' => 'Unloaded from Daily Load #' . $load->id,
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Daily load finished successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function dailySummary(Request $request)
    {
        $agent = $this->getAgent();
        return view('AgentView.daily-summary', compact('agent'));
    }

    public function stock()
    {
        $agent = $this->getAgent();
        $stock = collect([]);
        if ($agent) {
            $stock = \App\Models\StmBranchStock::where('agent_id', $agent->id)
                ->where('status', 1)
                ->with([
                    'productItem' => function ($q) {
                        $q->select('id', 'product_name', 'selling_price', 'wholesale_percentage', 'reference_number', 'pm_product_category_id')
                            ->with([
                                'category' => function ($q2) {
                                    $q2->select('id', 'category_name');
                                }
                            ]);
                    }
                ])
                ->get()
                ->groupBy('pm_product_item_id')
                ->map(function ($group) {
                    $first = $group->first();
                    $productItem = $first->productItem;
                    $category = $productItem && $productItem->category
                        ? ($productItem->category->category_name ?? 'Uncategorized')
                        : 'Uncategorized';

                    return [
                        'id' => $first->pm_product_item_id,
                        'product_name' => $productItem ? $productItem->product_name : 'Unknown',
                        'reference_number' => $productItem ? $productItem->reference_number : 'N/A',
                        'category' => $category,
                        'quantity' => $group->sum('quantity'),
                        'selling_price' => $productItem ? (float) $productItem->selling_price : 0,
                        'wholesale_price' => $productItem ? (float) $productItem->wholesale_price : 0,
                    ];
                })
                ->values();
        }
        return view('AgentView.stock', compact('agent', 'stock'));
    }

    public function guideVideos()
    {
        $agent = $this->getAgent();
        $videos = HsGuideVideo::where('status', 1)->orderBy('display_order')->get();
        return view('AgentView.guide-videos', compact('agent', 'videos'));
    }

    public function bakeryReturns()
    {
        $agent = $this->getAgent();
        return view('AgentView.bakery-returns.index', compact('agent'));
    }

    public function customerDetail($id)
    {
        try {
            $business = AdCustomerHasBusiness::with(['customer'])
                ->where('id', $id)
                ->first();

            if (! $business) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer business details not found',
                ], 404);
            }

            // Fetch outstanding balance
            $outstanding = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)
                ->selectRaw('SUM(net_price - total_amount_paid) as balance')
                ->first()->balance ?? 0;

            // Fetch recent invoices with items count and returns
            $recentInvoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)
                ->withCount('items')
                ->with(['newReturnItems.product', 'newReturnItems.invoice'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // Stats
            $totalInvoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)->count();
            $avgInvoiceValue = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)->avg('net_price') ?? 0;

            // Check if assigned to active daily load
            $agent = $this->getAgent();
            $agentId = $agent ? $agent->id : null;
            $isAssignedToLoad = false;
            if ($agentId) {
                // Find active daily load for this agent
                $activeLoad = AdDailyLoad::where('agent_id', $agentId)
                    ->where('status', 1) // Active
                    ->where('load_status', 3) // start
                    ->first();
                if ($activeLoad) {
                    $isAssignedToLoad = \App\Models\AdDailyLoadHasCustomer::where('daily_load_id', $activeLoad->id)
                        ->where('ad_customer_has_business_id', $business->id)
                        ->exists();
                }
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $business->id,
                    'name' => $business->customer->name ?? 'N/A',
                    'business_name' => $business->business_name ?: ($business->customer->name ?? 'N/A'),
                    'type' => $business->b2b_customer_type == 1 ? 'Retailer' : 'Wholesaler',
                    'image' => $business->customer_image ? asset($business->customer_image) : 'https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?w=400&h=400&fit=crop',
                    'address' => $business->address ?: ($business->customer->address ?? 'N/A'),
                    'contact_person_name' => $business->contact_person_name ?: ($business->customer->contact_person_name ?? 'N/A'),
                    'phone' => $business->contact_person_phone ?: ($business->customer->phone ?? 'N/A'),
                    'rating' => 4.8,
                    'latitude' => $business->latitude,
                    'longitude' => $business->longitude,
                    'since' => $business->created_at->format('Y'),
                    'outstanding' => (float) $outstanding,
                    'creditLimit' => (float) $business->credit_limit,
                    'is_assigned_to_active_load' => $isAssignedToLoad,
                    'stats' => [
                        'totalOrders' => $totalInvoices,
                        'avgOrderValue' => (float) $avgInvoiceValue,
                        'lastOrder' => $recentInvoices->first() ? $recentInvoices->first()->created_at->diffForHumans() : 'No orders yet',
                        'returnRate' => '0%',
                    ],
                    'recentOrders' => $recentInvoices->map(function ($invoice) {
                        return [
                            'id' => $invoice->id,
                            'invoice_number' => $invoice->invoice_number,
                            'date' => $invoice->created_at->format('M d, Y'),
                            'items' => $invoice->items_count ?? 0,
                            'total' => (float) $invoice->invoice_price,
                            'status' => $invoice->status ?? 'completed',
                            'created_at' => $invoice->created_at,
                            'payment_status' => $invoice->payment_status,
                            'invoice_price' => (float) $invoice->invoice_price,
                            'return_price' => (float) $invoice->return_price,
                            'net_price' => (float) $invoice->net_price,
                            'total_amount_paid' => (float) $invoice->total_amount_paid,
                            'return_items' => $invoice->newReturnItems->map(function ($rItem) {
                                return [
                                    'id' => $rItem->id,
                                    'product_name' => $rItem->product->product_name ?? 'N/A',
                                    'quantity' => (float) $rItem->return_quantity,
                                    'old_invoice_number' => $rItem->invoice->invoice_number ?? 'N/A',
                                ];
                            }),
                        ];
                    }),
                    'recentPayments' => AdCubusinessInvoicePayments::where('ad_customer_has_business_id', $business->id)
                        ->with('items.invoice')
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function ($payment) {
                            return [
                                'id' => $payment->id,
                                'receipt_number' => $payment->receipt_number,
                                'date' => $payment->payment_date,
                                'amount' => (float) $payment->amount,
                                'type' => $payment->payment_type == 1 ? 'Cash' : ($payment->payment_type == 2 ? 'Cheque' : 'Bank'),
                                'details' => $payment->items->map(function ($item) {
                                    return [
                                        'invoice_number' => $item->invoice->invoice_number ?? 'N/A',
                                        'applied_amount' => (float) $item->amount,
                                    ];
                                }),
                            ];
                        }),
                ],
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Web Customer Detail Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch customer details',
            ], 500);
        }
    }

    public function invoiceItems($invoiceId)
    {
        try {
            $items = \App\Models\AdCubusinessHasProductItem::with('product_item')
                ->where('ad_cubusiness_has_invoice_id', $invoiceId)
                ->get();

            $returnItems = \App\Models\AdCubusinessHasReturnProductItem::with(['product', 'invoice'])
                ->where('ad_new_invoice_id', $invoiceId)
                ->get();

            return response()->json([
                'status' => true,
                'items' => $items,
                'return_items' => $returnItems->map(function ($rItem) {
                    return [
                        'id' => $rItem->id,
                        'product' => $rItem->product,
                        'return_quantity' => (float) $rItem->return_quantity,
                        'unit_price' => (float) $rItem->unit_price,
                        'total_price' => (float) $rItem->total_price,
                        'reason' => $rItem->reason,
                        'old_invoice_number' => $rItem->invoice->invoice_number ?? 'N/A',
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Web Invoice Items Fetch Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch invoice items',
            ], 500);
        }
    }

    private function getB2BTypeName($type)
    {
        return match ($type) {
            CommonVariables::$b2bTypeWholesale => 'wholesale',
            CommonVariables::$b2bTypeRetail => 'retail_shop',
            CommonVariables::$b2bTypeRestaurant => 'restaurant',
            CommonVariables::$b2bTypeHotel => 'hotel',
            CommonVariables::$b2bTypeAgent => 'agent',
            default => 'other',
        };
    }

    private function getVisitScheduleName($type)
    {
        return match ($type) {
            CommonVariables::$visitScheduleWeekly => 'weekly',
            CommonVariables::$visitScheduleBiWeekly => 'bi-weekly',
            CommonVariables::$visitScheduleMonthly => 'monthly',
            CommonVariables::$visitScheduleOnDemand => 'on-demand',
            default => 'weekly',
        };
    }

    /**
     * Drivers Management - Display list
     */
    public function driversIndex()
    {
        $agent = $this->getAgent();
        $agentId = $agent ? $agent->id : null;

        $drivers = DmDriver::with(['agent', 'user'])
            ->when($agentId, function ($query) use ($agentId) {
                return $query->where('agent_id', $agentId);
            })
            ->get()
            ->map(function ($driver) {
                return [
                    'id' => $driver->id,
                    'driver_name' => $driver->driver_name,
                    'licence_number' => $driver->licence_number,
                    'licences_expire_date' => $driver->licences_expire_date,
                    'contact_number' => $driver->contact_number,
                    'status' => $driver->status,
                    'agent_id' => $driver->agent_id,
                    'agent_name' => $driver->agent ? $driver->agent->agent_name : null,
                    'user_id' => $driver->user_id,
                    'user_name' => $driver->user ? $driver->user->user_name : null,
                ];
            });

        return view('AgentView.drivers', compact('drivers'));
    }

    /**
     * Store a new driver
     */
    public function storeDriver(Request $request)
    {
        try {
            $agent = $this->getAgent();
            $agentId = $agent ? $agent->id : null;

            $validated = $request->validate([
                'driver_name' => 'required|string|max:255',
                'licence_number' => 'required|string|max:100',
                'licences_expire_date' => 'required|date',
                'contact_number' => 'required|string|max:20',
                'status' => 'nullable|integer|in:1,2',
            ]);

            DmDriver::create([
                'driver_name' => $validated['driver_name'],
                'licence_number' => $validated['licence_number'],
                'licences_expire_date' => $validated['licences_expire_date'],
                'contact_number' => $validated['contact_number'],
                'agent_id' => $agentId,
                'user_id' => null,
                'status' => $validated['status'] ?? 1,
                'is_added' => 1,
            ]);

            return redirect()->back()->with('success', 'Driver created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating driver: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update an existing driver
     */
    public function updateDriver(Request $request, $id)
    {
        try {
            $agent = $this->getAgent();
            $agentId = $agent ? $agent->id : null;

            $driver = DmDriver::findOrFail($id);

            if ($agentId && $driver->agent_id != $agentId) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            $validated = $request->validate([
                'driver_name' => 'required|string|max:255',
                'licence_number' => 'required|string|max:100',
                'licences_expire_date' => 'required|date',
                'contact_number' => 'required|string|max:20',
                'status' => 'nullable|integer|in:1,2',
            ]);

            $driver->update([
                'driver_name' => $validated['driver_name'],
                'licence_number' => $validated['licence_number'],
                'licences_expire_date' => $validated['licences_expire_date'],
                'contact_number' => $validated['contact_number'],
                'status' => $validated['status'] ?? $driver->status,
            ]);

            return redirect()->back()->with('success', 'Driver updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating driver: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Toggle driver status (active/inactive)
     */
    public function toggleDriverStatus($id)
    {
        try {
            $driver = DmDriver::findOrFail($id);
            $newStatus = $driver->status == 1 ? 2 : 1;
            $driver->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Driver status updated successfully',
                'newStatus' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling driver status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supervisors Management - Display list
     */
    public function supervisorsIndex()
    {
        $agent = $this->getAgent();
        $agentId = $agent ? $agent->id : null;

        $supervisors = SmSuperviser::with(['agent', 'user'])
            ->when($agentId, function ($query) use ($agentId) {
                return $query->where('agent_id', $agentId);
            })
            ->get()
            ->map(function ($supervisor) {
                return [
                    'id' => $supervisor->id,
                    'superviser_code' => $supervisor->superviser_code,
                    'superviser_name' => $supervisor->superviser_name,
                    'contact_number' => $supervisor->contact_number,
                    'nic_number' => $supervisor->nic_number,
                    'address' => $supervisor->address,
                    'status' => $supervisor->status,
                    'agent_id' => $supervisor->agent_id,
                    'agent_name' => $supervisor->agent ? $supervisor->agent->agent_name : null,
                    'user_id' => $supervisor->user_id,
                    'user_name' => $supervisor->user ? $supervisor->user->user_name : null,
                ];
            });

        return view('AgentView.supervisors', compact('supervisors'));
    }

    /**
     * Store a new supervisor
     */
    public function storeSupervisor(Request $request)
    {
        try {
            $agent = $this->getAgent();
            $agentId = $agent ? $agent->id : null;

            $validated = $request->validate([
                'superviser_name' => 'required|string|max:255',
                'superviser_code' => 'required|string|max:100|unique:sm_superviser,superviser_code',
                'contact_number' => 'required|string|max:20',
                'nic_number' => 'required|string|max:20',
                'address' => 'required|string',
                'status' => 'nullable|integer|in:1,2',
            ]);

            SmSuperviser::create([
                'superviser_name' => $validated['superviser_name'],
                'superviser_code' => $validated['superviser_code'],
                'contact_number' => $validated['contact_number'],
                'nic_number' => $validated['nic_number'],
                'address' => $validated['address'],
                'agent_id' => $agentId,
                'user_id' => null,
                'status' => $validated['status'] ?? 1,
                'is_added' => 1,
            ]);

            return redirect()->back()->with('success', 'Supervisor created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating supervisor: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update an existing supervisor
     */
    public function updateSupervisor(Request $request, $id)
    {
        try {
            $agent = $this->getAgent();
            $agentId = $agent ? $agent->id : null;

            $supervisor = SmSuperviser::findOrFail($id);

            if ($agentId && $supervisor->agent_id != $agentId) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }

            $validated = $request->validate([
                'superviser_name' => 'required|string|max:255',
                'superviser_code' => 'required|string|max:100|unique:sm_superviser,superviser_code,' . $id,
                'contact_number' => 'required|string|max:20',
                'nic_number' => 'required|string|max:20',
                'address' => 'required|string',
                'status' => 'nullable|integer|in:1,2',
            ]);

            $supervisor->update([
                'superviser_name' => $validated['superviser_name'],
                'superviser_code' => $validated['superviser_code'],
                'contact_number' => $validated['contact_number'],
                'nic_number' => $validated['nic_number'],
                'address' => $validated['address'],
                'status' => $validated['status'] ?? $supervisor->status,
            ]);

            return redirect()->back()->with('success', 'Supervisor updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating supervisor: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Toggle supervisor status (active/inactive)
     */
    public function toggleSupervisorStatus($id)
    {
        try {
            $supervisor = SmSuperviser::findOrFail($id);
            $newStatus = $supervisor->status == 1 ? 2 : 1;
            $supervisor->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Supervisor status updated successfully',
                'newStatus' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling supervisor status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Route Management - Display routes list for Agent Portal
     */
    public function routeManageIndex()
    {
        $agent = $this->getAgent();
        $agentId = $agent ? $agent->id : null;

        $routes = AdRoute::with(['agent', 'customers.customer'])
            ->when($agentId, function ($query) use ($agentId) {
                return $query->where('agent_id', $agentId);
            })
            ->get()
            ->map(function ($route) {
                $routeCustomers = $route->customers->map(function ($business) {
                    $customer = $business->customer;
                    return [
                        'id' => $business->id,
                        'businessName' => $business->business_name ?? ($customer ? $customer->name : 'Unknown'),
                        'address' => $business->address ?? ($customer ? $customer->address : ''),
                        'phone' => $business->contact_person_phone ?? ($customer ? $customer->phone : ''),
                        'contactPerson' => $business->contact_person_name ?? '',
                        'b2bType' => $this->getB2BTypeName($business->b2b_customer_type ?? 0),
                        'latitude' => (float) ($business->latitude ?? ($customer ? $customer->latitude : 6.9271)),
                        'longitude' => (float) ($business->longitude ?? ($customer ? $customer->longitude : 79.8612)),
                        'stopSequence' => $business->pivot->stop_sequence ?? null,
                        'distanceKm' => $business->pivot->distance_km ?? null,
                        'durationMinutes' => $business->pivot->duration_minutes ?? null,
                    ];
                })->sortBy('stopSequence')->values()->toArray();

                return [
                    'id' => $route->id,
                    'routeCode' => $route->route_code,
                    'routeName' => $route->route_name,
                    'description' => $route->description,
                    'target_distance_km' => $route->target_distance_km,
                    'target_duration_hours' => $route->target_duration_hours,
                    'agentId' => $route->agent_id,
                    'agentName' => $route->agent ? $route->agent->agent_name : null,
                    'agentCode' => $route->agent ? $route->agent->agent_code : null,
                    'status' => $route->status,
                    'customers' => $routeCustomers,
                    'customerCount' => count($routeCustomers),
                ];
            });

        $agents = AdAgent::orderBy('agent_name')->where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                ];
            });

        $customers = CmCustomer::with(['businessDetails'])
            ->leftJoin('ad_customer_has_business', 'cm_customer.id', '=', 'ad_customer_has_business.customer_id')
            ->leftJoin('ad_route_has_customers', function ($join) {
                $join->on('ad_customer_has_business.id', '=', 'ad_route_has_customers.ad_customer_has_business_id')
                    ->on('ad_customer_has_business.route_id', '=', 'ad_route_has_customers.route_id');
            })
            ->select(
                'cm_customer.*',
                'ad_route_has_customers.distance_km as saved_distance',
                'ad_route_has_customers.duration_minutes as saved_duration'
            )
            ->whereIn('cm_customer.customer_type', [CommonVariables::$customerTypeB2B, CommonVariables::$customerTypeB2C])
            ->when($agentId, function ($query) use ($agentId) {
                return $query->where('ad_customer_has_business.agent_id', $agentId);
            })
            ->get()
            ->map(function ($c) {
                $d = $c->businessDetails;
                if (!$d) {
                    $d = (object) [
                        'b2b_customer_type' => null,
                        'contact_person_name' => null,
                        'contact_person_phone' => null,
                        'route_id' => null,
                        'stop_sequence' => null,
                    ];
                }

                return [
                    'id' => $c->id,
                    'businessName' => $c->name,
                    'b2bType' => $this->getB2BTypeName($d->b2b_customer_type ?? 0),
                    'location' => [
                        'address' => $c->address ?? 'Unknown',
                        'city' => 'Colombo',
                        'latitude' => $c->latitude ?? 6.9271,
                        'longitude' => $c->longitude ?? 79.8612,
                    ],
                    'contact' => [
                        'contactPerson' => $d->contact_person_name ?? '',
                        'phoneNumber' => $c->phone ?? '',
                    ],
                    'assignedRouteId' => $d->route_id ?? null,
                    'stopSequence' => $d->stop_sequence ?? null,
                    'savedDistance' => $c->saved_distance,
                    'savedDuration' => $c->saved_duration,
                ];
            })
            ->filter()
            ->values();

        $supervisors = SmSuperviser::where('status', 1)
            ->when($agentId, function ($query) use ($agentId) {
                return $query->where('agent_id', $agentId);
            })
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'superviser_name' => $s->superviser_name
                ];
            });

        $googleMapsKey = config('services.google.maps_key');

        return view('AgentView.routeManagement', compact('routes', 'agents', 'customers', 'googleMapsKey', 'supervisors', 'agent'));
    }
}
