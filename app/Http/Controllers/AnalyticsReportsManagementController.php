<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AdCubusinessHasInvoice;
use App\Models\AdCubusinessInvoicePayments;
use App\Models\AdDailyLoad;
use App\Models\AdReturnProductStock;
use App\Models\AdRoute;
use App\Models\AdAgent;

class AnalyticsReportsManagementController extends Controller
{
    public function analyticsReportsDashboardIndex()
    {
        return view('errors.under-development');
    }

    public function analyticsReportsSalesAnalyticsIndex()
    {
        return view('errors.under-development');
    }

    public function analyticsReportsInventoryReportsIndex()
    {
        return view('errors.under-development');
    }

    public function analyticsReportsProductionReportsIndex()
    {
        return view('errors.under-development');
    }

    public function analyticsReportsFinancialReportsIndex()
    {
        return view('errors.under-development');
    }

    public function analyticsReportsDailySummaryIndex()
    {
        $agents = AdAgent::where('status', 1)->get();
        return view('analyticsReports.dailySummary', [
            'pageTitle' => 'Daily Summary Report',
            'agents' => $agents
        ]);
    }

    public function getWebDailySummary(Request $request)
    {
        try {
            $date = $request->query('date', date('Y-m-d'));
            $agentId = $request->query('agent_id');
            $loadId = $request->query('load_id');
            
            // Determine route IDs and user IDs for filtering
            if ($agentId) {
                $agent = AdAgent::find($agentId);
                $routeIds = AdRoute::where('agent_id', $agentId)->pluck('id');
                $userIds = $agent && $agent->user_id ? [$agent->user_id] : [];
            } else {
                // If no specific agent, use all agents/routes
                $routeIds = AdRoute::pluck('id');
                $userIds = AdAgent::whereNotNull('user_id')->pluck('user_id');
            }
            
            // 1. Sales Summary
            $invoicesQuery = AdCubusinessHasInvoice::whereDate('created_at', $date);
            
            if ($loadId) {
                $invoicesQuery->where('ad_daily_load_id', $loadId);
            } else {
                $invoicesQuery->where(function($query) use ($routeIds, $userIds) {
                    $query->whereHas('business', function($q) use ($routeIds) {
                        $q->whereIn('route_id', $routeIds);
                    });
                    if (!empty($userIds) && count($userIds) > 0) {
                        $query->orWhereIn('created_by', $userIds);
                    }
                });
            }

            $invoices = $invoicesQuery->with(['business', 'items.product'])->get();

            // 1. Cost - Total value of Order Requests
            $costQuery = \App\Models\StmOrderRequest::whereDate('created_at', $date);
            if ($agentId) {
                $costQuery->where('agent_id', $agentId);
            }
            // Note: StmOrderRequest doesn't have load_id, so if load_id is selected, cost might not be fully accurate to the load
            $totalCost = $costQuery->sum('grand_total');

            // 2. Sales
            $totalSales = $invoices->sum('net_price');
            $itemCount = $invoices->sum(function ($invoice) {
                return $invoice->items->sum('quantity');
            });

            $grossProfit = 0;
            $netProfit = 0;
            $returnProfitLoss = 0;

            // 3. Returns
            $invoiceIds = $invoices->pluck('id');
            
            $returns = \App\Models\AdCubusinessHasReturnProductItem::whereIn('ad_new_invoice_id', $invoiceIds)->get();

            $totalReturnsValue = $returns->sum('total_price');

            // 4. Payment Breakdown & Credit
            $paymentsQuery = AdCubusinessInvoicePayments::whereDate('created_at', $date);
            
            if ($loadId) {
                // filter payments by the invoices associated with this load
                $paymentsQuery->whereIn('ad_cubusiness_has_invoice_id', $invoiceIds);
            } else {
                $paymentsQuery->whereHas('business', function ($q) use ($routeIds) {
                    $q->whereIn('route_id', $routeIds);
                });
            }
            $payments = $paymentsQuery->get();

            $totalCredit = $invoices->sum(function ($inv) {
                return max(0, $inv->net_price - $inv->total_amount_paid);
            });

            $paymentBreakdown = [
                'cash' => $payments->where('payment_type', 1)->sum('amount'),
                'credit' => $totalCredit,
                'cheque' => $payments->where('payment_type', 2)->sum('amount'),
                'bank_transfer' => $payments->where('payment_type', 3)->sum('amount'),
                'total_collected' => $payments->sum('amount')
            ];

            // 4. Daily Loads for this date
            $loadsQuery = clone AdDailyLoad::whereDate('load_date', $date);
            
            if ($loadId) {
                $loadsQuery->where('id', $loadId);
            } elseif ($agentId) {
                $loadsQuery->whereIn('route_id', $routeIds);
            } else {
                 $loadsQuery->whereNotNull('route_id'); // All distribution loads
            }

            $loads = $loadsQuery->with(['route', 'vehicle', 'driver'])
                ->get()
                ->map(function($load) {
                    return [
                        'id' => $load->id,
                        'route_name' => $load->route->route_name ?? 'N/A',
                        'vehicle' => $load->vehicle->vehicle_number ?? 'N/A',
                        'status' => $load->load_status,
                    ];
                });

            $loadTransactions = [];
            foreach ($invoices as $invoice) {
                $salesItems = [];
                foreach ($invoice->items as $item) {
                    $salesItems[] = [
                        'product_name' => $item->product->product_name ?? 'N/A',
                        'quantity' => (float)$item->quantity,
                        'unit_price' => (float)$item->unit_price,
                        'total_price' => (float)$item->total_price,
                    ];
                }

                $returnItems = [];
                $invoiceReturns = $returns->where('ad_new_invoice_id', $invoice->id);
                foreach ($invoiceReturns as $ret) {
                    $returnItems[] = [
                        'product_name' => $ret->product->product_name ?? 'N/A',
                        'quantity' => (float)$ret->return_quantity,
                        'unit_price' => (float)$ret->unit_price,
                        'total_price' => (float)$ret->total_price,
                    ];
                }

                $loadTransactions[] = [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'business_name' => $invoice->business->business_name ?? 'Walk-in Customer',
                    'sales_amount' => (float)$invoice->net_price,
                    'return_amount' => (float)$invoiceReturns->sum('total_price'),
                    'sales_items' => $salesItems,
                    'return_items' => $returnItems,
                ];
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'date' => $date,
                    'summary' => [
                        'total_sales' => (float)$totalSales,
                        'total_cost' => (float)$totalCost,
                        'gross_profit' => (float)$grossProfit,
                        'item_count' => (float)$itemCount,
                    ],
                    'returns' => [
                        'total_value' => (float)$totalReturnsValue,
                        'count' => $returns->count(),
                        'profit_impact' => (float)$returnProfitLoss
                    ],
                    'profit' => [
                        'net_profit' => (float)$netProfit,
                        'margin_percentage' => $totalSales > 0 ? round(($netProfit / $totalSales) * 100, 2) : 0
                    ],
                    'payments' => $paymentBreakdown,
                    'loads' => $loads,
                    'load_transactions' => $loadTransactions
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Web Get Daily Sales Summary Failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch summary: ' . $e->getMessage()], 500);
        }
    }
}
