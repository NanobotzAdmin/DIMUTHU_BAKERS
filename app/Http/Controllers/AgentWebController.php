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
                ->orderBy('load_date', 'desc')
                ->get();
        }
        return view('AgentView.daily-loads', compact('agent', 'loads'));
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
}
