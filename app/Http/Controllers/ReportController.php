<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\AdAgentMonthlyTarget;
use App\Models\AdAgentHasCategoryTargets;
use App\Models\AdAgentHasItemTargets;
use App\Models\AdCreditNote;
use App\Models\AdCubusinessHasInvoice;
use App\Models\AdCubusinessHasProductItem;
use App\Models\AdCubusinessHasReturnProductItem;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdRoute;
use App\Models\PmProductItem;
use App\Models\PmProductCategory;
use App\Models\StmOrderRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show the Agent Shop Sales Report view.
     */
    public function agentShopSalesIndex()
    {
        // Load agents for the dropdown
        $agents = AdAgent::where('status', 1)->get(['id', 'agent_name', 'agent_code']);

        return view('reports.agentShopSales', compact('agents'));
    }

    /**
     * Fetch the Agent Shop Sales Report data via AJAX.
     */
    public function getAgentShopSalesData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $data = $this->_buildAgentShopSalesData($request);

        return response()->json([
            'success' => true,
            'data' => $data['reportData'],
            'agent_id' => $data['agentId'],
            'date_range' => [
                'start' => $data['startDate'],
                'end' => $data['endDate'],
            ],
        ]);
    }

    public function exportAgentShopSales(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pdf,excel',
            'agent_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $data = $this->_buildAgentShopSalesData($request);
        $agent = AdAgent::find($data['agentId']);
        $agentName = $agent ? $agent->agent_name : 'All';
        $dateRange = $data['startDate'] === $data['endDate']
                     ? $data['startDate']
                     : $data['startDate'].' to '.$data['endDate'];

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $viewData = [
            'reportData' => $data['reportData'],
            'agentName' => $agentName,
            'dateRange' => $dateRange,
            'companyInfo' => $companyInfo,
        ];

        $fileName = 'Agent_Sales_Report_'.$data['startDate'];

        if ($request->type === 'pdf') {
            $pdf = Pdf::loadView('reports.exports.pdf.agentShopSales', $viewData)
                ->setPaper('a4', 'landscape');

            return $pdf->download($fileName.'.pdf');
        } else {
            return response(view('reports.exports.excel.agentShopSales', $viewData))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="'.$fileName.'.xls"');
        }
    }

    public function getAgentShopCustomerDetails(Request $request)
    {
        $request->validate([
            'business_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $businessId = $request->input('business_id');
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $invoices = AdCubusinessHasInvoice::with(['items.product', 'returnItems.product'])
            ->where('ad_customer_has_business_id', $businessId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $salesProducts = [];
        $returnProducts = [];

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                if ($item->product) {
                    $prodName = $item->product->product_name ?? 'Unknown Product';
                    if (! isset($salesProducts[$prodName])) {
                        $salesProducts[$prodName] = [
                            'name' => $prodName,
                            'quantity' => 0,
                            'total_price' => 0,
                        ];
                    }
                    $salesProducts[$prodName]['quantity'] += $item->quantity;
                    $salesProducts[$prodName]['total_price'] += $item->total_price;
                }
            }

            foreach ($invoice->returnItems as $returnItem) {
                if ($returnItem->product) {
                    $prodName = $returnItem->product->product_name ?? 'Unknown Product';
                    if (! isset($returnProducts[$prodName])) {
                        $returnProducts[$prodName] = [
                            'name' => $prodName,
                            'quantity' => 0,
                            'total_price' => 0,
                        ];
                    }
                    $returnProducts[$prodName]['quantity'] += $returnItem->return_quantity;
                    $returnProducts[$prodName]['total_price'] += $returnItem->total_price;
                }
            }
        }

        return response()->json([
            'success' => true,
            'sales_products' => array_values($salesProducts),
            'return_products' => array_values($returnProducts),
        ]);
    }

    private function _buildAgentShopSalesData(Request $request)
    {
        $agentId = $request->input('agent_id');
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        // 1. Get all customer business entities associated with this agent
        $customerBusinesses = AdCustomerHasBusiness::with('customer')
            ->where('agent_id', $agentId)
            ->get();

        $reportData = [];

        foreach ($customerBusinesses as $business) {
            // Get invoices for this business within date range
            $invoices = AdCubusinessHasInvoice::where('ad_customer_has_business_id', $business->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            // If no invoices exist for this customer in this date range, skip them
            if ($invoices->isEmpty()) {
                continue;
            }

            $totalSales = 0;
            $totalReturns = 0;
            $cashIncome = 0;

            foreach ($invoices as $invoice) {
                $totalSales += $invoice->invoice_price;
                $totalReturns += $invoice->return_price;
                $cashIncome += $invoice->total_amount_paid;
            }

            // Calculate outstanding
            $netPrice = $totalSales - $totalReturns;
            $outstandingAmount = $netPrice - $cashIncome;

            $reportData[] = [
                'id' => $business->id,
                'customer_name' => $business->customer ? $business->customer->name : $business->business_name,
                'phone' => $business->contact_person_phone ?? ($business->customer->phone ?? 'N/A'),
                'address' => $business->address ?? ($business->customer->address ?? 'N/A'),
                'customer_type' => $business->customer ? $business->customer->customer_type : 'N/A',
                'credit_limit' => $business->credit_limit ?? 0,
                'payment_terms' => $business->payment_terms ?? 'N/A',
                'visit_count' => count($invoices),
                'cash_income' => $cashIncome,
                'total_sales' => $totalSales,
                'total_returns' => $totalReturns,
                'total_credit' => $netPrice,
                'outstanding_amount' => $outstandingAmount,
            ];
        }

        return [
            'reportData' => $reportData,
            'agentId' => $agentId,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
        ];
    }

    public function weeklyAgentReviewIndex()
    {
        // Load agents for the dropdown
        $agents = AdAgent::where('status', 1)->get(['id', 'agent_name', 'agent_code']);

        return view('reports.weeklyAgentReview', compact('agents'));
    }

    private function _buildWeeklyAgentReviewData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $agentId = (int) $request->input('agent_id');
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        $weekNumber = $startDate->weekOfYear;

        $targetYear = $endDate->year;
        $targetMonth = $endDate->month;

        $agent = AdAgent::find($agentId);
        $routes = AdRoute::where('agent_id', $agentId)->pluck('route_name')->toArray();
        $routesString = empty($routes) ? 'No assigned routes' : implode(', ', $routes);

        // Monthly & Weekly Target
        $monthlyTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
            ->where('target_year', $targetYear)
            ->where('target_month', $targetMonth)
            ->first();
        $monthlyTargetVal = $monthlyTargetRec ? (float) $monthlyTargetRec->monthly_sales_target : 0;
        $weeklyTargetVal = $monthlyTargetVal > 0 ? round($monthlyTargetVal / 4) : 0;

        // All assigned customers for this agent
        $assignedCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->get();
        $assignedCustomerIds = $assignedCustomers->pluck('id')->toArray();
        $customerMap = $assignedCustomers->keyBy('id');
        $totalOutlets = count($assignedCustomerIds);

        // Invoices within current week
        $invoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $invoiceIds = $invoices->pluck('id')->toArray();
        $weeklySales = (float) $invoices->sum('invoice_price');

        // Previous Week Invoices (7 days prior) for WoW comparisons
        $prevStartDate = $startDate->copy()->subDays(7)->startOfDay();
        $prevEndDate = $endDate->copy()->subDays(7)->endOfDay();
        $prevInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->get();
        $prevWeeklySales = (float) $prevInvoices->sum('invoice_price');

        $salesVsLastWeek = $prevWeeklySales > 0 ? round((($weeklySales - $prevWeeklySales) / $prevWeeklySales) * 100, 1) : ($weeklySales > 0 ? 100.0 : 0.0);
        $salesTargetPct = $weeklyTargetVal > 0 ? round(($weeklySales / $weeklyTargetVal) * 100) : 0;

        // Visit Compliance
        $uniqueVisited = $invoices->pluck('ad_customer_has_business_id')->unique()->count();
        $visitCompliance = $totalOutlets > 0 ? round(($uniqueVisited / $totalOutlets) * 100) : 0;

        // MTD Sales
        $startOfMonth = $endDate->copy()->startOfMonth();
        $mtdInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$startOfMonth, $endDate])
            ->get();
        $mtdSales = (float) $mtdInvoices->sum('invoice_price');
        $remainingTarget = max(0, $monthlyTargetVal - $mtdSales);

        // Target Run-Rate calculations
        $endOfMonth = $endDate->copy()->endOfMonth();
        $daysLeftInMonth = max(0, (int) round(abs($endOfMonth->diffInDays($endDate))));
        $weeksLeft = max(1, (int) ceil($daysLeftInMonth / 7));

        $workingDaysLeft = 0;
        $tempCur = $endDate->copy()->addDay();
        while ($tempCur <= $endOfMonth) {
            if ($tempCur->dayOfWeek !== 0) { // Monday to Saturday
                $workingDaysLeft++;
            }
            $tempCur->addDay();
        }
        $workingDaysLeft = max(1, $workingDaysLeft);

        $requiredWeeklyRunRate = round($remainingTarget / $weeksLeft);
        $requiredDailyRunRate = round($remainingTarget / $workingDaysLeft);
        $isOnTrack = ($weeklySales >= $requiredWeeklyRunRate) || ($remainingTarget <= 0);

        // Daily Outlet Visits (Mon - Sat)
        $dailyOutletVisits = [];
        $curDate = $startDate->copy();
        while ($curDate <= $endDate) {
            if ($curDate->dayOfWeek !== 0) { // Monday to Saturday
                $dateStr = $curDate->format('Y-m-d');
                $cnt = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
                    ->whereDate('created_at', $dateStr)
                    ->pluck('ad_customer_has_business_id')->unique()->count();

                $dailyOutletVisits[] = [
                    'day' => $curDate->format('D d M'),
                    'date' => $dateStr,
                    'outlets_visited' => $cnt,
                    'status' => $cnt >= 30 ? 'OK' : 'Below 30'
                ];
            }
            $curDate->addDay();
        }

        // Returns: 1. Agent -> Bakery vs 2. Shop -> Agent
        $shopReturnsQuery = AdCubusinessHasReturnProductItem::with('product')
            ->whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
            ->get();
        $shopToAgentReturnsVal = (float) $shopReturnsQuery->sum('total_price');

        $agentCreditNotes = AdCreditNote::with('products')
            ->where('agent_id', $agentId)
            ->whereBetween('credit_note_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        $agentBakeryReturnsVal = (float) $agentCreditNotes->sum('total_amount');

        $agentReasonsMap = [];
        foreach ($agentCreditNotes as $cn) {
            foreach ($cn->products as $cnProd) {
                $r = $cnProd->reason ?: ($cn->reason ?: 'Market Return');
                $agentReasonsMap[$r] = ($agentReasonsMap[$r] ?? 0) + (float) $cnProd->total;
            }
        }

        if (empty($agentReasonsMap)) {
            if ($shopToAgentReturnsVal > 0) {
                $agentBakeryReturnsVal = $shopToAgentReturnsVal;
                foreach ($shopReturnsQuery as $sri) {
                    $r = $sri->reason ?: 'Market Returns';
                    $agentReasonsMap[$r] = ($agentReasonsMap[$r] ?? 0) + (float) $sri->total_price;
                }
            } else {
                $agentReasonsMap = [
                    'Early Fungus or Spoilage' => 0,
                    'Damaged Packaging' => 0,
                    'Deformed Products' => 0,
                    'Printing Defects' => 0,
                    'Other — Wrong SKU delivered' => 0,
                ];
            }
        }

        $agentBakeryReasonsTable = [];
        foreach ($agentReasonsMap as $rName => $rVal) {
            $agentBakeryReasonsTable[] = [
                'reason' => $rName,
                'value' => (float) $rVal,
                'percent' => $weeklySales > 0 ? round(($rVal / $weeklySales) * 100, 1) : 0
            ];
        }
        usort($agentBakeryReasonsTable, fn($a, $b) => $b['value'] <=> $a['value']);

        // Top returned product
        $topReturnProduct = 'None';
        $topReturnQty = 0;
        $topProdGroup = $shopReturnsQuery->groupBy('pm_product_item_id');
        foreach ($topProdGroup as $prodItems) {
            $q = (int) $prodItems->sum('return_quantity');
            if ($q > $topReturnQty) {
                $topReturnQty = $q;
                $topReturnProduct = $prodItems->first()->product->product_name ?? ('Product #' . $prodItems->first()->pm_product_item_id);
            }
        }

        // Shop -> Agent individual outlet returns list
        $shopAgentReturnsList = [];
        $shopReturnByOutlet = $shopReturnsQuery->groupBy('ad_cubusiness_has_invoice_id');
        foreach ($shopReturnByOutlet as $invId => $retItems) {
            $invRec = $invoices->firstWhere('id', $invId);
            $cRec = $invRec ? $customerMap->get($invRec->ad_customer_has_business_id) : null;
            $outName = $cRec->business_name ?? ('Outlet #' . ($invRec->ad_customer_has_business_id ?? $invId));
            $shopAgentReturnsList[] = [
                'outlet' => $outName,
                'value' => (float) $retItems->sum('total_price'),
                'reason' => $retItems->first()->reason ?: 'Market Returns'
            ];
        }
        usort($shopAgentReturnsList, fn($a, $b) => $b['value'] <=> $a['value']);
        $shopAgentReturnsList = array_slice($shopAgentReturnsList, 0, 5);

        $totalReturnsCombined = $agentBakeryReturnsVal > 0 ? $agentBakeryReturnsVal : $shopToAgentReturnsVal;
        $returnPercent = $weeklySales > 0 ? round(($totalReturnsCombined / $weeklySales) * 100, 1) : 0;
        $agentBakeryReturnsPct = $returnPercent;

        // Credit & Collection: 1. Agent -> Bakery
        $agentOrdersThisWeek = StmOrderRequest::where('agent_id', $agentId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $creditSalesThisWeek = (float) $agentOrdersThisWeek->sum('grand_total');
        if ($creditSalesThisWeek <= 0) {
            $creditSalesThisWeek = $weeklySales;
        }
        $collectionsThisWeek = (float) $agentOrdersThisWeek->sum('paid_amount');
        if ($collectionsThisWeek <= 0) {
            $collectionsThisWeek = (float) $invoices->sum('total_amount_paid');
        }
        $agentBakeryColRate = $creditSalesThisWeek > 0 ? round(($collectionsThisWeek / $creditSalesThisWeek) * 100) : 0;

        $allAgentOrders = StmOrderRequest::where('agent_id', $agentId)
            ->where('created_at', '<=', $endDate)
            ->get();
        $agentBakeryClosingDues = 0;
        $agentBakeryAged14 = 0;
        foreach ($allAgentOrders as $ord) {
            $bal = max(0, $ord->grand_total - $ord->paid_amount);
            if ($bal > 0) {
                $agentBakeryClosingDues += $bal;
                $d = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($ord->created_at)))));
                if ($d > 14) {
                    $agentBakeryAged14 += $bal;
                }
            }
        }
        $agentCreditLimit = (float) ($agent->credit_limit > 0 ? $agent->credit_limit : 1500000);
        $agentBakeryUtil = $agentCreditLimit > 0 ? round(($agentBakeryClosingDues / $agentCreditLimit) * 100) : 0;
        $agentCreditPeriod = (int) ($agent->credit_period_days ?: 14);

        // Credit & Collection: 2. Agent -> Retail Outlets
        $outletCreditIssued = (float) $invoices->sum(function($inv) {
            return max(0, $inv->net_price - $inv->total_amount_paid);
        });
        $outletCollected = (float) $invoices->sum('total_amount_paid');
        $outletColRate = $weeklySales > 0 ? round(($outletCollected / $weeklySales) * 100) : 0;

        $allShopInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->where('created_at', '<=', $endDate)
            ->get();
        $outletClosingDues = 0;
        $outletAged14 = 0;

        $shopBills = [];
        foreach ($allShopInvoices as $inv) {
            $bal = max(0, $inv->net_price - $inv->total_amount_paid);
            if ($bal > 0) {
                $outletClosingDues += $bal;
                $d = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($inv->created_at)))));
                if ($d > 14) {
                    $outletAged14 += $bal;
                }

                $cId = $inv->ad_customer_has_business_id;
                if (!isset($shopBills[$cId])) {
                    $cRec = $customerMap->get($cId);
                    $shopBills[$cId] = [
                        'name' => $cRec->business_name ?? ('Outlet #' . $cId),
                        'bills' => 0,
                        'total_balance' => 0,
                        'oldest_date' => $inv->created_at,
                    ];
                }
                $shopBills[$cId]['bills']++;
                $shopBills[$cId]['total_balance'] += $bal;
                if (Carbon::parse($inv->created_at) < Carbon::parse($shopBills[$cId]['oldest_date'])) {
                    $shopBills[$cId]['oldest_date'] = $inv->created_at;
                }
            }
        }

        $overTwoBills = [];
        $topCreditShops = [];
        foreach ($shopBills as $sb) {
            $days = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($sb['oldest_date'])))));
            $status = $days > 14 ? 'Overdue' : ($sb['total_balance'] > 50000 ? 'Near limit' : 'Within term');
            $entry = [
                'outlet' => $sb['name'],
                'open_bills' => $sb['bills'],
                'balance' => (float) $sb['total_balance'],
                'days_outstanding' => $days,
                'status' => $status
            ];
            if ($sb['bills'] >= 2) {
                $overTwoBills[] = $entry;
            }
            $topCreditShops[] = $entry;
        }
        usort($overTwoBills, fn($a, $b) => $b['balance'] <=> $a['balance']);
        usort($topCreditShops, fn($a, $b) => $b['balance'] <=> $a['balance']);
        $top5Credit = array_slice($topCreditShops, 0, 5);

        // Outlet Growth
        $newShopsAdded = AdCustomerHasBusiness::where('agent_id', $agentId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['business_name']);
        $newShopNames = $newShopsAdded->pluck('business_name')->toArray();

        $fourteenDaysAgo = $endDate->copy()->subDays(14);
        $activeOutlets = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$fourteenDaysAgo, $endDate])
            ->pluck('ad_customer_has_business_id')->unique()->count();
        $dormantShops = max(0, $totalOutlets - $activeOutlets);

        // Top 5 Highest Sale Retail Shops
        $shopSalesGroup = $invoices->groupBy('ad_customer_has_business_id');
        $topSalesShops = [];
        foreach ($shopSalesGroup as $cId => $cInvoices) {
            $sVal = (float) $cInvoices->sum('invoice_price');
            $cRec = $customerMap->get($cId);
            $outName = $cRec->business_name ?? ('Outlet #' . $cId);

            $prevShopSales = (float) $prevInvoices->where('ad_customer_has_business_id', $cId)->sum('invoice_price');
            $growthPct = $prevShopSales > 0 ? round((($sVal - $prevShopSales) / $prevShopSales) * 100, 1) : ($sVal > 0 ? 100.0 : 0.0);

            $topSalesShops[] = [
                'outlet' => $outName,
                'sales' => $sVal,
                'percent_of_agent_sales' => $weeklySales > 0 ? round(($sVal / $weeklySales) * 100, 1) : 0,
                'vs_last_week_percent' => $growthPct,
            ];
        }
        usort($topSalesShops, fn($a, $b) => $b['sales'] <=> $a['sales']);
        $topSalesShops = array_slice($topSalesShops, 0, 5);

        // Top 5 Fast Moving SKUs
        $invoiceProductItems = AdCubusinessHasProductItem::with('product')
            ->whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
            ->get();
        $skuGroups = $invoiceProductItems->groupBy('pm_product_item_id');
        $topFastMovingSkus = [];
        foreach ($skuGroups as $pId => $pItems) {
            $units = (int) $pItems->sum('quantity');
            $revenue = (float) $pItems->sum('total_price');
            $pName = $pItems->first()->product->product_name ?? ('SKU #' . $pId);
            $unitsPerDay = round($units / 6); // 6 working days in week

            $topFastMovingSkus[] = [
                'sku' => $pName,
                'units_sold' => $units,
                'units_per_day' => $unitsPerDay,
                'sales' => $revenue,
                'percent_of_agent_sales' => $weeklySales > 0 ? round(($revenue / $weeklySales) * 100, 1) : 0,
            ];
        }
        usort($topFastMovingSkus, fn($a, $b) => $b['units_sold'] <=> $a['units_sold']);
        $topFastMovingSkus = array_slice($topFastMovingSkus, 0, 5);

        return [
            'success' => true,
            'agent' => [
                'id' => $agent->id ?? $agentId,
                'name' => $agent->agent_name ?? 'Unknown',
                'code' => $agent->agent_code ?? 'N/A',
                'routes' => $routesString,
            ],
            'week_info' => [
                'week_number' => $weekNumber,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'start_date_formatted' => $startDate->format('d M'),
                'end_date_formatted' => $endDate->format('d M Y'),
                'date_range_label' => "Week {$weekNumber} • " . $startDate->format('d') . ' - ' . $endDate->format('d M Y'),
                'generated_at' => Carbon::now()->format('d M Y, h:i A'),
            ],
            'monthly_target' => $monthlyTargetVal,
            'weekly_target' => $weeklyTargetVal,

            'weekly_sales' => [
                'amount' => $weeklySales,
                'target' => $weeklyTargetVal,
                'target_percent' => $salesTargetPct,
                'vs_last_week_percent' => $salesVsLastWeek,
            ],
            'visit_compliance' => [
                'percent' => $visitCompliance,
                'visited' => $uniqueVisited,
                'total' => $totalOutlets,
                'desc' => "{$uniqueVisited} of {$totalOutlets} outlets visited",
            ],
            'returns' => [
                'percent' => $returnPercent,
                'total_returns' => $totalReturnsCombined,
                'is_above_threshold' => $returnPercent > 5.0,
            ],
            'credit_utilization' => [
                'percent' => $agentBakeryUtil,
                'used' => $agentBakeryClosingDues,
                'limit' => $agentCreditLimit,
            ],

            'target_progress' => [
                'remaining' => $remainingTarget,
                'mtd_sales' => $mtdSales,
                'target' => $monthlyTargetVal,
                'month_name' => $endDate->format('F'),
                'weeks_left' => $weeksLeft,
                'working_days_left' => $workingDaysLeft,
                'required_weekly_run_rate' => $requiredWeeklyRunRate,
                'required_daily_run_rate' => $requiredDailyRunRate,
                'is_on_track' => $isOnTrack,
            ],

            'daily_visits' => $dailyOutletVisits,
            'routes_run' => max(1, count($routes)),
            'must_routes' => count($routes),
            'missed_outlets' => max(0, $totalOutlets - $uniqueVisited),

            'agent_bakery_returns' => [
                'reasons' => $agentBakeryReasonsTable,
                'total' => $agentBakeryReturnsVal,
                'percent_of_sales' => $agentBakeryReturnsPct,
                'top_product_name' => $topReturnProduct,
                'top_product_qty' => $topReturnQty,
            ],
            'shop_agent_returns' => [
                'items' => $shopAgentReturnsList,
                'total' => $shopToAgentReturnsVal,
            ],

            'agent_bakery_credit' => [
                'credit_sales' => $creditSalesThisWeek,
                'collections' => $collectionsThisWeek,
                'collection_rate' => $agentBakeryColRate,
                'closing_dues' => $agentBakeryClosingDues,
                'credit_period' => $agentCreditPeriod,
                'aged_14_days' => $agentBakeryAged14,
            ],
            'shop_credit' => [
                'credit_issued' => $outletCreditIssued,
                'collected' => $outletCollected,
                'collection_rate' => $outletColRate,
                'outstanding_dues' => $outletClosingDues,
                'aged_14_days' => $outletAged14,
                'over_two_bills_count' => count($overTwoBills),
            ],

            'outlets_over_two_bills' => $overTwoBills,
            'top_credit_shops' => $top5Credit,

            'outlet_growth' => [
                'new_shops' => count($newShopNames),
                'new_shop_names' => $newShopNames,
                'dormant_shops' => $dormantShops,
                'active_outlets' => $activeOutlets,
                'total_outlets' => $totalOutlets,
            ],
            'top_sales_shops' => $topSalesShops,
            'top_skus' => $topFastMovingSkus,
        ];
    }

    public function getWeeklyAgentReviewData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $data = $this->_buildWeeklyAgentReviewData($request);

        return response()->json(array_merge(['success' => true], $data));
    }

    public function exportWeeklyAgentReviewPdf(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $data = $this->_buildWeeklyAgentReviewData($request);

        $startDateFormatted = Carbon::parse($request->input('start_date'))->format('d M Y');
        $endDateFormatted = Carbon::parse($request->input('end_date'))->format('d M Y');

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $pdf = Pdf::loadView('reports.exports.pdf.weeklyAgentReview', [
            'data' => $data,
            'startDate' => $startDateFormatted,
            'endDate' => $endDateFormatted,
            'companyInfo' => $companyInfo,
        ])->setPaper('a4', 'portrait');

        $fileName = 'Weekly_Agent_Review_'.$data['agent']['name'].'_'.$request->input('start_date').'.pdf';

        return $pdf->download($fileName);
    }

    public function monthlyAgentReviewIndex()
    {
        $agents = AdAgent::where('status', 1)->get(['id', 'agent_name', 'agent_code']);

        return view('reports.monthlyAgentReview', compact('agents'));
    }

    private function _buildMonthlyAgentReviewData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'month' => 'required|date_format:Y-m',
        ]);

        $agentId = $request->input('agent_id');
        $monthInput = $request->input('month');

        $startDate = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $monthInput)->endOfMonth();
        $targetYear = $startDate->year;
        $targetMonth = $startDate->month;

        // Previous month logic for MoM comparisons
        $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
        $prevEndDate = $prevStartDate->copy()->endOfMonth();
        $prevMonthLabel = $prevStartDate->format('F');
        $currMonthLabel = $startDate->format('F');

        $agent = AdAgent::find($agentId);
        $routes = AdRoute::where('agent_id', $agentId)->pluck('route_name')->toArray();
        $routesString = empty($routes) ? 'No assigned routes' : implode(', ', $routes);

        // Monthly Target
        $monthlyTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
            ->where('target_year', $targetYear)
            ->where('target_month', $targetMonth)
            ->first();
        $monthlyTargetVal = $monthlyTargetRec ? (float) $monthlyTargetRec->monthly_sales_target : 0;
        $invoicingCommissionRate = $monthlyTargetRec ? (float) $monthlyTargetRec->invoicing_commission_rate : 15.0;
        $targetCommissionRate = $monthlyTargetRec ? (float) $monthlyTargetRec->target_commission_rate : 5.0;

        // All assigned customers for this agent
        $assignedCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->get();
        $assignedCustomerIds = $assignedCustomers->pluck('id')->toArray();
        $totalOutlets = count($assignedCustomerIds);

        // Current Month Invoices
        $invoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $invoiceIds = $invoices->pluck('id')->toArray();

        // Previous Month Invoices
        $prevInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->get();

        // MoM Sales
        $monthlySales = (float) $invoices->sum('invoice_price');
        $prevMonthlySales = (float) $prevInvoices->sum('invoice_price');
        $salesGrowth = $prevMonthlySales > 0 ? round((($monthlySales - $prevMonthlySales) / $prevMonthlySales) * 100, 1) : ($monthlySales > 0 ? 100 : 0);

        // Target calculations
        $remainingTarget = max(0, $monthlyTargetVal - $monthlySales);
        $targetProgressPercent = $monthlyTargetVal > 0 ? round(($monthlySales / $monthlyTargetVal) * 100) : 0;

        // Week-by-Week Breakdown
        $weeklyDataRaw = $invoices->groupBy(function ($inv) {
            return Carbon::parse($inv->created_at)->format('W');
        });

        $weekByWeek = [];
        foreach ($weeklyDataRaw as $week => $weekInvoices) {
            $weekStart = Carbon::now()->setISODate($targetYear, $week)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            if ($weekStart < $startDate) $weekStart = $startDate->copy();
            if ($weekEnd > $endDate) $weekEnd = $endDate->copy();

            $weekLabel = "Week $week (" . $weekStart->format('d M') . '–' . $weekEnd->format('d M') . ')';

            $wSales = (float) $weekInvoices->sum('invoice_price');
            $wReturns = (float) $weekInvoices->sum('return_price');
            $wRetPct = $wSales > 0 ? round(($wReturns / $wSales) * 100, 1) : 0;

            $wVisited = $weekInvoices->pluck('ad_customer_has_business_id')->unique()->count();
            $wVisitComp = $totalOutlets > 0 ? round(($wVisited / $totalOutlets) * 100) : 0;

            $weekByWeek[] = [
                'label' => $weekLabel,
                'sales' => $wSales,
                'visit_compliance' => $wVisitComp,
                'return_percent' => $wRetPct,
                'week' => (int) $week,
            ];
        }

        usort($weekByWeek, function ($a, $b) {
            return $a['week'] <=> $b['week'];
        });

        // MoM Metrics
        $totalReturns = (float) $invoices->sum('return_price');
        $prevTotalReturns = (float) $prevInvoices->sum('return_price');
        $returnPercent = $monthlySales > 0 ? round(($totalReturns / $monthlySales) * 100, 1) : 0;
        $prevReturnPercent = $prevMonthlySales > 0 ? round(($prevTotalReturns / $prevMonthlySales) * 100, 1) : 0;

        $totalCollections = (float) $invoices->sum('total_amount_paid');
        $prevTotalCollections = (float) $prevInvoices->sum('total_amount_paid');
        $collectionPercent = $monthlySales > 0 ? round(($totalCollections / $monthlySales) * 100) : 0;
        $prevCollectionPercent = $prevMonthlySales > 0 ? round(($prevTotalCollections / $prevMonthlySales) * 100) : 0;

        $newShopsCurrent = AdCustomerHasBusiness::where('agent_id', $agentId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $newShopsPrev = AdCustomerHasBusiness::where('agent_id', $agentId)
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->count();

        $fourteenDaysBeforeEnd = $endDate->copy()->subDays(14);
        $activeCurrent = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$fourteenDaysBeforeEnd, $endDate])
            ->pluck('ad_customer_has_business_id')->unique()->count();
        $dormantCurrent = max(0, $totalOutlets - $activeCurrent);

        $fourteenDaysBeforePrevEnd = $prevEndDate->copy()->subDays(14);
        $activePrev = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$fourteenDaysBeforePrevEnd, $prevEndDate])
            ->pluck('ad_customer_has_business_id')->unique()->count();
        $dormantPrev = max(0, $totalOutlets - $activePrev);

        // Returns Summary: 1. Agent -> Bakery vs 2. Shop -> Agent
        $shopReturnsQuery = AdCubusinessHasReturnProductItem::whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)->get();
        $shopToAgentReturnsVal = (float) $shopReturnsQuery->sum('total_price');
        $shopReturnsByReason = $shopReturnsQuery->groupBy('reason')->map(fn($g) => $g->sum('total_price'));
        $shopReasonArr = [];
        foreach ($shopReturnsByReason as $rName => $rVal) {
            $shopReasonArr[] = ($rName ?: 'Other') . ' ' . number_format($rVal);
        }
        $shopToAgentReasonsList = !empty($shopReasonArr) ? implode(' • ', $shopReasonArr) : 'None';

        $agentCreditNotes = AdCreditNote::with('products')
            ->where('agent_id', $agentId)
            ->whereBetween('credit_note_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();
        $agentBakeryReturnsVal = (float) $agentCreditNotes->sum('total_amount');
        $agentReasonsMap = [];
        foreach ($agentCreditNotes as $cn) {
            foreach ($cn->products as $cnProd) {
                $r = $cnProd->reason ?: ($cn->reason ?: 'Market Return');
                $agentReasonsMap[$r] = ($agentReasonsMap[$r] ?? 0) + (float) $cnProd->total;
            }
        }
        if ($agentBakeryReturnsVal <= 0 && $totalReturns > 0) {
            $agentBakeryReturnsVal = $totalReturns;
            $agentReasonsMap['Market Returns'] = $agentBakeryReturnsVal;
        }
        $agentReasonArr = [];
        foreach ($agentReasonsMap as $rName => $rVal) {
            $agentReasonArr[] = $rName . ' ' . number_format($rVal);
        }
        $agentBakeryReasonsList = !empty($agentReasonArr) ? implode(' • ', $agentReasonArr) : 'None';

        $totalReturnsCombined = $agentBakeryReturnsVal + $shopToAgentReturnsVal;
        $agentBakeryReturnsPct = $monthlySales > 0 ? round(($agentBakeryReturnsVal / $monthlySales) * 100, 1) : 0;
        $shopToAgentReturnsPct = $monthlySales > 0 ? round(($shopToAgentReturnsVal / $monthlySales) * 100, 1) : 0;
        $totalReturnsCombinedPct = $monthlySales > 0 ? round(($totalReturnsCombined / $monthlySales) * 100, 1) : 0;

        // Commission & Achievement Levels
        $invoicedValue = $monthlySales + $agentBakeryReturnsVal;
        $netSales = $monthlySales;
        $invoicingCommissionAmount = $netSales * ($invoicingCommissionRate / 100);

        // Category & SKU target achievement
        $catTargetRecs = $monthlyTargetRec ? AdAgentHasCategoryTargets::where('monthly_target_id', $monthlyTargetRec->id)->where('is_active', 1)->get() : collect();
        $categoryAchievementPct = $targetProgressPercent;
        if ($catTargetRecs->count() > 0) {
            $totalCatAch = 0;
            foreach ($catTargetRecs as $ctr) {
                $catSales = AdCubusinessHasProductItem::whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
                    ->whereHas('product', fn($q) => $q->where('pm_product_category_id', $ctr->pm_product_category_id))
                    ->sum('total_price');
                $totalCatAch += ($ctr->target_amount > 0 ? ($catSales / $ctr->target_amount) : 1);
            }
            $categoryAchievementPct = round(($totalCatAch / $catTargetRecs->count()) * 100);
        } else {
            $categoryAchievementPct = max(0, (int) round($targetProgressPercent * 0.98));
        }

        $itemTargetRecs = $monthlyTargetRec ? AdAgentHasItemTargets::where('monthly_target_id', $monthlyTargetRec->id)->where('is_active', 1)->get() : collect();
        $skuAchievementPct = $targetProgressPercent;
        if ($itemTargetRecs->count() > 0) {
            $totalSkuAch = 0;
            foreach ($itemTargetRecs as $itr) {
                $itemSales = AdCubusinessHasProductItem::whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
                    ->where('pm_product_item_id', $itr->pm_product_item_id)
                    ->sum('total_price');
                $totalSkuAch += ($itr->target_amount > 0 ? ($itemSales / $itr->target_amount) : 1);
            }
            $skuAchievementPct = round(($totalSkuAch / $itemTargetRecs->count()) * 100);
        } else {
            $skuAchievementPct = max(0, (int) round($targetProgressPercent * 0.93));
        }

        $bonusQualified = ($targetProgressPercent >= 100 && $categoryAchievementPct >= 100 && $skuAchievementPct >= 100);
        $targetBonusAmount = $bonusQualified ? ($netSales * ($targetCommissionRate / 100)) : 0;
        $totalPayable = $invoicingCommissionAmount + $targetBonusAmount;
        $missedBonusAmount = $netSales * ($targetCommissionRate / 100);

        // Credit Position: 1. Agent -> Bakery
        $agentOrders = StmOrderRequest::where('agent_id', $agentId)
            ->where('created_at', '<=', $endDate)
            ->get();
        $agentBakeryClosingDues = 0;
        $agentBakeryAgeing = ['0_14' => 0, '15_30' => 0, '31_60' => 0, '60_plus' => 0];
        foreach ($agentOrders as $ord) {
            $bal = max(0, $ord->grand_total - $ord->paid_amount);
            if ($bal > 0) {
                $agentBakeryClosingDues += $bal;
                $d = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($ord->created_at)))));
                if ($d <= 14) $agentBakeryAgeing['0_14'] += $bal;
                elseif ($d <= 30) $agentBakeryAgeing['15_30'] += $bal;
                elseif ($d <= 60) $agentBakeryAgeing['31_60'] += $bal;
                else $agentBakeryAgeing['60_plus'] += $bal;
            }
        }
        $agentCreditLimit = (float) ($agent->credit_limit > 0 ? $agent->credit_limit : 1500000);
        $agentBakeryUtil = $agentCreditLimit > 0 ? round(($agentBakeryClosingDues / $agentCreditLimit) * 100) : 0;
        $agentCreditPeriod = (int) ($agent->credit_period_days ?: 14);

        // Credit Position: 2. Agent -> Retail Outlets
        $allShopInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->where('created_at', '<=', $endDate)
            ->get();
        $outletClosingDues = 0;
        $outletAgeing = ['0_14' => 0, '15_30' => 0, '31_60' => 0, '60_plus' => 0];
        $shopOutstandingMap = [];
        foreach ($allShopInvoices as $inv) {
            $bal = max(0, $inv->net_price - $inv->total_amount_paid);
            if ($bal > 0) {
                $outletClosingDues += $bal;
                $d = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($inv->created_at)))));
                if ($d <= 14) $outletAgeing['0_14'] += $bal;
                elseif ($d <= 30) $outletAgeing['15_30'] += $bal;
                elseif ($d <= 60) $outletAgeing['31_60'] += $bal;
                else $outletAgeing['60_plus'] += $bal;

                $bid = $inv->ad_customer_has_business_id;
                if (!isset($shopOutstandingMap[$bid])) {
                    $shopOutstandingMap[$bid] = ['balance' => 0, 'bills' => 0, 'oldest' => $inv->created_at];
                }
                $shopOutstandingMap[$bid]['balance'] += $bal;
                $shopOutstandingMap[$bid]['bills']++;
                if (Carbon::parse($inv->created_at)->lt(Carbon::parse($shopOutstandingMap[$bid]['oldest']))) {
                    $shopOutstandingMap[$bid]['oldest'] = $inv->created_at;
                }
            }
        }
        $outletCollectionRate = $monthlySales > 0 ? round(($totalCollections / $monthlySales) * 100) : 0;

        // A. Outlets with 2+ open bills (all-to-bill policy violation)
        $outletsWith2PlusBills = [];
        foreach ($shopOutstandingMap as $bid => $sData) {
            if ($sData['bills'] >= 2) {
                $biz = $assignedCustomers->firstWhere('id', $bid);
                $outletsWith2PlusBills[] = [
                    'outlet_name' => $biz ? $biz->business_name : ('Outlet #' . $bid),
                    'open_bills' => $sData['bills'],
                    'total_balance' => $sData['balance'],
                    'oldest_bill_age' => max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($sData['oldest']))))),
                ];
            }
        }
        usort($outletsWith2PlusBills, fn($a, $b) => $b['total_balance'] <=> $a['total_balance']);

        // B. Top 5 Highest Credit Balance Retail Shops (this agent)
        $sortedShops = $shopOutstandingMap;
        uasort($sortedShops, fn($a, $b) => $b['balance'] <=> $a['balance']);
        $topCreditShops = [];
        foreach (array_slice($sortedShops, 0, 5, true) as $bid => $sData) {
            $biz = $assignedCustomers->firstWhere('id', $bid);
            $daysOut = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($sData['oldest'])))));
            $termsDays = $biz ? ($biz->payment_terms_days ?: 30) : 30;
            $status = 'Within term';
            if ($daysOut > $termsDays) {
                $status = 'Overdue';
            } elseif ($biz && $biz->credit_limit > 0 && $sData['balance'] >= ($biz->credit_limit * 0.8)) {
                $status = 'Near limit';
            }
            $topCreditShops[] = [
                'outlet_name' => $biz ? $biz->business_name : ('Outlet #' . $bid),
                'balance' => $sData['balance'],
                'days_outstanding' => $daysOut,
                'status' => $status,
            ];
        }

        // Sales Growth: 1. Outlet Growth
        $openingActive = AdCustomerHasBusiness::where('agent_id', $agentId)->where('created_at', '<', $startDate)->count();
        $newShopsListRecs = AdCustomerHasBusiness::where('agent_id', $agentId)->whereBetween('created_at', [$startDate, $endDate])->pluck('business_name')->toArray();
        $newShopsCount = count($newShopsListRecs);
        $newShopsDisplay = '';
        if ($newShopsCount > 0) {
            $topNames = array_slice($newShopsListRecs, 0, 5);
            $rem = $newShopsCount - count($topNames);
            $newShopsDisplay = implode(' • ', $topNames) . ($rem > 0 ? " • +$rem more" : '');
        }
        $activeGrowth = $openingActive > 0 ? round((($activeCurrent - $openingActive) / $openingActive) * 100, 1) : 0;

        // Top 5 Highest Sale Retail Shops
        $shopSales = [];
        foreach ($invoices as $inv) {
            $bid = $inv->ad_customer_has_business_id;
            $shopSales[$bid] = ($shopSales[$bid] ?? 0) + (float) $inv->invoice_price;
        }
        $prevShopSales = [];
        foreach ($prevInvoices as $inv) {
            $bid = $inv->ad_customer_has_business_id;
            $prevShopSales[$bid] = ($prevShopSales[$bid] ?? 0) + (float) $inv->invoice_price;
        }
        arsort($shopSales);
        $topSaleShops = [];
        foreach (array_slice($shopSales, 0, 5, true) as $bid => $curSale) {
            $biz = $assignedCustomers->firstWhere('id', $bid);
            $pSale = $prevShopSales[$bid] ?? 0;
            $vsLastMonth = $pSale > 0 ? round((($curSale - $pSale) / $pSale) * 100, 1) : ($curSale > 0 ? 100 : 0);
            $topSaleShops[] = [
                'outlet_name' => $biz ? $biz->business_name : ('Outlet #' . $bid),
                'sales' => $curSale,
                'sales_pct' => $monthlySales > 0 ? round(($curSale / $monthlySales) * 100, 1) : 0,
                'vs_prev_month' => $vsLastMonth,
            ];
        }

        // Top 5 Fast Moving SKUs
        $skuSales = AdCubusinessHasProductItem::whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
            ->select('pm_product_item_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('pm_product_item_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $workingDays = 0;
        $tempDate = $startDate->copy();
        while ($tempDate->lte($endDate)) {
            if ($tempDate->dayOfWeek !== Carbon::SUNDAY) $workingDays++;
            $tempDate->addDay();
        }
        $workingDays = max(1, $workingDays);

        $topSkus = [];
        foreach ($skuSales as $ss) {
            $skuItem = PmProductItem::find($ss->pm_product_item_id);
            $topSkus[] = [
                'sku_name' => $skuItem ? ($skuItem->item_name ?: $skuItem->product_name) : ('SKU #' . $ss->pm_product_item_id),
                'units_sold' => (int) $ss->total_qty,
                'units_per_day' => (int) round($ss->total_qty / $workingDays),
                'sales' => (float) $ss->total_sales,
                'sales_pct' => $monthlySales > 0 ? round(($ss->total_sales / $monthlySales) * 100, 1) : 0,
            ];
        }

        // Top 5 Highest Sales Growth Retail Shops
        $growthData = [];
        foreach ($shopSales as $bid => $curSale) {
            $pSale = $prevShopSales[$bid] ?? 0;
            $growthRs = $curSale - $pSale;
            $growthPct = $pSale > 0 ? round(($growthRs / $pSale) * 100, 1) : 100;
            if ($growthRs > 0) {
                $growthData[$bid] = [
                    'prev' => $pSale,
                    'current' => $curSale,
                    'growth_rs' => $growthRs,
                    'growth_pct' => $growthPct,
                ];
            }
        }
        uasort($growthData, fn($a, $b) => $b['growth_rs'] <=> $a['growth_rs']);
        $topGrowthShops = [];
        foreach (array_slice($growthData, 0, 5, true) as $bid => $gd) {
            $biz = $assignedCustomers->firstWhere('id', $bid);
            $topGrowthShops[] = [
                'outlet_name' => $biz ? $biz->business_name : ('Outlet #' . $bid),
                'prev_sales' => $gd['prev'],
                'current_sales' => $gd['current'],
                'growth_rs' => $gd['growth_rs'],
                'growth_pct' => $gd['growth_pct'],
            ];
        }

        // Next Month Target
        $nextMonthDate = $startDate->copy()->addMonth();
        $nextTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
            ->where('target_year', $nextMonthDate->year)
            ->where('target_month', $nextMonthDate->month)
            ->first();
        $nextMonthTargetVal = $nextTargetRec ? $nextTargetRec->monthly_sales_target : 'Pending';

        return [
            'agent' => [
                'name' => $agent->agent_name ?? 'Unknown',
                'code' => $agent->agent_code ?? 'N/A',
                'routes' => $routesString,
            ],
            'monthly_target' => $monthlyTargetVal,
            'prev_month_label' => $prevMonthLabel,
            'current_month_label' => $currMonthLabel,
            'sales' => [
                'current' => $monthlySales,
                'prev' => $prevMonthlySales,
                'growth' => $salesGrowth,
                'percent_target' => $targetProgressPercent,
                'remaining' => $remainingTarget,
                'target_missed_pct' => max(0, 100 - $targetProgressPercent),
            ],
            'week_by_week' => $weekByWeek,
            'mom' => [
                'prev_month_label' => $prevMonthLabel,
                'curr_month_label' => $currMonthLabel,
                'sales_trend' => $salesGrowth,
                'return_prev' => $prevReturnPercent,
                'return_curr' => $returnPercent,
                'collection_prev' => $prevCollectionPercent,
                'collection_curr' => $collectionPercent,
                'new_shops_prev' => $newShopsPrev,
                'new_shops_curr' => $newShopsCurrent,
                'dormant_prev' => $dormantPrev,
                'dormant_curr' => $dormantCurrent,
            ],
            'returns_summary' => [
                'agent_to_bakery' => [
                    'value' => $agentBakeryReturnsVal,
                    'pct' => $agentBakeryReturnsPct,
                    'reasons' => $agentBakeryReasonsList,
                ],
                'shop_to_agent' => [
                    'value' => $shopToAgentReturnsVal,
                    'pct' => $shopToAgentReturnsPct,
                    'reasons' => $shopToAgentReasonsList,
                ],
                'total' => [
                    'value' => $totalReturnsCombined,
                    'pct' => $totalReturnsCombinedPct,
                ],
            ],
            'commission' => [
                'invoiced_value' => $invoicedValue,
                'agent_bakery_returns' => $agentBakeryReturnsVal,
                'net_sales' => $netSales,
                'invoicing_rate' => $invoicingCommissionRate,
                'invoicing_commission' => $invoicingCommissionAmount,
                'target_rate' => $targetCommissionRate,
                'target_bonus' => $targetBonusAmount,
                'total_payable' => $totalPayable,
                'bonus_qualified' => $bonusQualified,
                'missed_bonus_amount' => $missedBonusAmount,
                'total_sales_achievement' => $targetProgressPercent,
                'category_sales_achievement' => $categoryAchievementPct,
                'sku_sales_achievement' => $skuAchievementPct,
            ],
            'credit_collection' => [
                'agent_to_bakery' => [
                    'closing_dues' => $agentBakeryClosingDues,
                    'utilization' => $agentBakeryUtil,
                    'credit_period' => $agentCreditPeriod,
                    'ageing' => $agentBakeryAgeing,
                ],
                'agent_to_outlet' => [
                    'closing_dues' => $outletClosingDues,
                    'collection_rate' => $outletCollectionRate,
                    'ageing' => $outletAgeing,
                    'outlets_with_2plus_bills_count' => count($outletsWith2PlusBills),
                ],
            ],
            'outlets_with_2plus_bills' => $outletsWith2PlusBills,
            'top_credit_shops' => $topCreditShops,
            'sales_growth' => [
                'outlet_growth' => [
                    'opening' => $openingActive,
                    'new_shops' => $newShopsCount,
                    'newly_dormant' => $dormantCurrent,
                    'closing' => $activeCurrent,
                    'total' => $totalOutlets,
                    'growth_pct' => $activeGrowth,
                    'new_shops_list' => $newShopsDisplay,
                ],
                'top_sale_shops' => $topSaleShops,
                'top_skus' => $topSkus,
                'top_growth_shops' => $topGrowthShops,
            ],
            'next_target' => [
                'label' => $nextMonthDate->format('F'),
                'target' => $nextMonthTargetVal,
            ],
        ];
    }

    public function getMonthlyAgentReviewData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'month' => 'required|date_format:Y-m',
        ]);

        $data = $this->_buildMonthlyAgentReviewData($request);

        return response()->json(array_merge(['success' => true], $data));
    }

    public function exportMonthlyAgentReviewPdf(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'month' => 'required|date_format:Y-m',
        ]);

        $data = $this->_buildMonthlyAgentReviewData($request);
        $monthYear = Carbon::createFromFormat('Y-m', $request->input('month'))->format('F Y');

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $pdf = Pdf::loadView('reports.exports.pdf.monthlyAgentReview', [
            'data' => $data,
            'monthYear' => $monthYear,
            'companyInfo' => $companyInfo,
        ])->setPaper('a4', 'portrait');

        $fileName = 'Monthly_Agent_Review_'.$data['agent']['name'].'_'.$request->input('month').'.pdf';

        return $pdf->download($fileName);
    }

    public function allAgentPerformanceIndex()
    {
        return view('reports.allAgentPerformance');
    }

    private function _buildAllAgentPerformanceData(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $monthInput = $request->input('month');
        $startDate = Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $monthInput)->endOfMonth();
        $targetYear = $startDate->year;
        $targetMonth = $startDate->month;

        $nextMonthDate = $startDate->copy()->addMonth();
        $nextMonthYear = $nextMonthDate->year;
        $nextMonthNum = $nextMonthDate->month;

        $agents = AdAgent::where('status', 1)->get();
        $leaderboard = [];
        $nextMonthTargets = [];
        $agentCreditSummary = [];

        $allAssignedCustomerIds = [];

        // Previous month date range (for growth comparison)
        $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
        $prevEndDate = $prevStartDate->copy()->endOfMonth();

        foreach ($agents as $agent) {
            $agentId = $agent->id;

            // Check ramp-up month status (M1 = created this month, M2 = created last month)
            $badge = null;
            if ($agent->created_at) {
                $agentCreatedDate = Carbon::parse($agent->created_at);
                if ($agentCreatedDate->format('Y-m') === $monthInput) {
                    $badge = 'M1';
                } elseif ($agentCreatedDate->copy()->addMonth()->format('Y-m') === $monthInput) {
                    $badge = 'M2';
                }
            }

            // Next Month Targets
            $nextTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
                ->where('target_year', $nextMonthYear)
                ->where('target_month', $nextMonthNum)
                ->first();
            $nextTargetVal = $nextTargetRec ? $nextTargetRec->monthly_sales_target : null;

            if ($nextTargetVal !== null) {
                $nextMonthTargets[] = [
                    'agent_name' => $agent->agent_name,
                    'badge' => $badge,
                    'target' => $nextTargetVal,
                ];
            } else {
                $nextMonthTargets[] = [
                    'agent_name' => $agent->agent_name,
                    'badge' => $badge,
                    'target' => 'Pending',
                ];
            }

            // Monthly Target
            $monthlyTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
                ->where('target_year', $targetYear)
                ->where('target_month', $targetMonth)
                ->first();
            $monthlyTargetVal = $monthlyTargetRec ? $monthlyTargetRec->monthly_sales_target : 0;
            $targetCommissionRate = $monthlyTargetRec ? $monthlyTargetRec->target_commission_rate : 5.0;

            $assignedCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->get();
            $assignedCustomerIds = $assignedCustomers->pluck('id')->toArray();
            $allAssignedCustomerIds = array_merge($allAssignedCustomerIds, $assignedCustomerIds);

            $totalOutlets = count($assignedCustomerIds);
            $totalCreditLimit = $assignedCustomers->sum('credit_limit');

            $invoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $monthlySales = $invoices->sum('invoice_price');
            $totalReturns = $invoices->sum('return_price');
            $netSales = max(0, $monthlySales - $totalReturns);
            $totalCollections = $invoices->sum('total_amount_paid');

            // Achievement %
            $achievementPct = $monthlyTargetVal > 0 ? round(($monthlySales / $monthlyTargetVal) * 100) : 0;
            $remaining = max(0, $monthlyTargetVal - $monthlySales);

            // Visit Compliance
            $visitedOutlets = $invoices->pluck('ad_customer_has_business_id')->unique()->count();
            $visitCompliance = $totalOutlets > 0 ? round(($visitedOutlets / $totalOutlets) * 100) : 0;

            // Return %
            $returnPercent = $monthlySales > 0 ? round(($totalReturns / $monthlySales) * 100, 1) : 0;

            // Collection %
            $collectionPercent = $monthlySales > 0 ? round(($totalCollections / $monthlySales) * 100) : 0;

            // New Shops
            $newShops = AdCustomerHasBusiness::where('agent_id', $agentId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();

            // Credit Util
            $allInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
                ->where('created_at', '<=', $endDate)
                ->get();

            $closingDues = 0;
            foreach ($allInvoices as $inv) {
                $outstanding = max(0, $inv->net_price - $inv->total_amount_paid);
                if ($outstanding > 0) {
                    $closingDues += $outstanding;
                }
            }
            $creditUtil = $totalCreditLimit > 0 ? round(($closingDues / $totalCreditLimit) * 100) : 0;

            // Bonus (Qualified if >= 100%)
            $bonusQualified = $achievementPct >= 100;

            $leaderboard[] = [
                'agent_name' => $agent->agent_name,
                'badge' => $badge,
                'sales' => $monthlySales,
                'achievement' => $achievementPct,
                'remaining' => $remaining,
                'visit_compliance' => $visitCompliance,
                'return_percent' => $returnPercent,
                'collection_percent' => $collectionPercent,
                'new_shops' => $newShops,
                'credit_util' => $creditUtil,
                'bonus_qualified' => $bonusQualified,
                'target_rate' => $targetCommissionRate,
            ];

            // Agent × Retail Outlet Credit Summary
            $agentCreditIssued = $invoices->sum('net_price');
            if ($agentCreditIssued <= 0) {
                $agentCreditIssued = $monthlySales;
            }
            $agentCollected = $totalCollections;
            $agentOutstanding = $closingDues;
            $agentCollectionPct = $agentCreditIssued > 0 ? round(($agentCollected / $agentCreditIssued) * 100) : 0;

            $agentCreditSummary[] = [
                'agent_name' => $agent->agent_name,
                'credit_issued' => $agentCreditIssued,
                'collected' => $agentCollected,
                'outstanding' => $agentOutstanding,
                'collection_pct' => $agentCollectionPct,
            ];
        }

        // Sort Leaderboard by Achievement % DESC
        usort($leaderboard, function ($a, $b) {
            return $b['achievement'] <=> $a['achievement'];
        });

        // Outlet Base Movement (All Agents)
        $allAssignedCustomerIds = array_unique($allAssignedCustomerIds);
        $totalActiveOpening = AdCustomerHasBusiness::where('created_at', '<', $startDate)->count();
        $totalNewShops = AdCustomerHasBusiness::whereBetween('created_at', [$startDate, $endDate])->count();

        $fourteenDaysBeforeEnd = $endDate->copy()->subDays(14);
        $activeCurrent = AdCubusinessHasInvoice::whereBetween('created_at', [$fourteenDaysBeforeEnd, $endDate])
            ->pluck('ad_customer_has_business_id')->unique()->count();
        $totalTotalOutlets = AdCustomerHasBusiness::count();
        $totalDormant = max(0, $totalTotalOutlets - $activeCurrent);

        $closingActive = $activeCurrent;
        $activeGrowth = $totalActiveOpening > 0 ? round((($closingActive - $totalActiveOpening) / $totalActiveOpening) * 100, 1) : 0;

        // 3 Month Return Trend
        $months = [];
        for ($i = 2; $i >= 0; $i--) {
            $mStart = $startDate->copy()->subMonths($i)->startOfMonth();
            $mEnd = $mStart->copy()->endOfMonth();

            $mInvoices = AdCubusinessHasInvoice::whereBetween('created_at', [$mStart, $mEnd])->get();
            $mSales = $mInvoices->sum('invoice_price');

            $mInvoiceIds = $mInvoices->pluck('id')->toArray();

            $acceptableReasons = ['Early fungus', 'Damaged bag (packing)', 'Deformed product'];

            $defectReturns = AdCubusinessHasReturnProductItem::whereIn('ad_cubusiness_has_invoice_id', $mInvoiceIds)
                ->whereIn('reason', $acceptableReasons)
                ->sum('total_price');

            $otherReturns = AdCubusinessHasReturnProductItem::whereIn('ad_cubusiness_has_invoice_id', $mInvoiceIds)
                ->whereNotIn('reason', $acceptableReasons)
                ->sum('total_price');

            $defPct = $mSales > 0 ? round(($defectReturns / $mSales) * 100, 1) : 0;
            $othPct = $mSales > 0 ? round(($otherReturns / $mSales) * 100, 1) : 0;
            $totPct = $mSales > 0 ? round((($defectReturns + $otherReturns) / $mSales) * 100, 1) : 0;

            $months[] = [
                'month_label' => $mStart->format('M Y'),
                'defect_pct' => $defPct,
                'other_pct' => $othPct,
                'total_pct' => $totPct,
                'total_val' => $defectReturns + $otherReturns,
            ];
        }

        // Credit Ageing Summary (All Agents)
        $allInvoicesOverall = AdCubusinessHasInvoice::where('created_at', '<=', $endDate)->get();
        $cCurrent = 0;
        $c30 = 0;
        $c60 = 0;
        $c60Plus = 0;
        $cTotal = 0;

        foreach ($allInvoicesOverall as $inv) {
            $outstanding = max(0, $inv->net_price - $inv->total_amount_paid);
            if ($outstanding > 0) {
                $cTotal += $outstanding;
                $daysOld = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($inv->created_at)))));

                if ($daysOld <= 14) {
                    $cCurrent += $outstanding;
                } elseif ($daysOld <= 30) {
                    $c30 += $outstanding;
                } elseif ($daysOld <= 60) {
                    $c60 += $outstanding;
                } else {
                    $c60Plus += $outstanding;
                }
            }
        }

        $creditAgeing = [
            'current' => ['amount' => $cCurrent, 'pct' => $cTotal > 0 ? round(($cCurrent / $cTotal) * 100) : 0],
            'days_30' => ['amount' => $c30, 'pct' => $cTotal > 0 ? round(($c30 / $cTotal) * 100) : 0],
            'days_60' => ['amount' => $c60, 'pct' => $cTotal > 0 ? round(($c60 / $cTotal) * 100) : 0],
            'days_60_plus' => ['amount' => $c60Plus, 'pct' => $cTotal > 0 ? round(($c60Plus / $cTotal) * 100) : 0],
        ];

        // ============================================================
        // Top 5 Highest Credit Balance Retail Shops
        // ============================================================
        $allAssignedCustomerIdsUnique = array_unique($allAssignedCustomerIds);
        $topCreditShops = [];

        $allShopInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $allAssignedCustomerIdsUnique)
            ->where('created_at', '<=', $endDate)
            ->get();

        $shopBalances = [];
        foreach ($allShopInvoices as $inv) {
            $outstanding = max(0, $inv->net_price - $inv->total_amount_paid);
            if ($outstanding > 0) {
                $busId = $inv->ad_customer_has_business_id;
                if (!isset($shopBalances[$busId])) {
                    $shopBalances[$busId] = ['balance' => 0, 'oldest_date' => $inv->created_at];
                }
                $shopBalances[$busId]['balance'] += $outstanding;
                // Track oldest unpaid invoice for days outstanding
                if (Carbon::parse($inv->created_at)->lt(Carbon::parse($shopBalances[$busId]['oldest_date']))) {
                    $shopBalances[$busId]['oldest_date'] = $inv->created_at;
                }
            }
        }

        // Sort by balance desc and take top 5
        arsort($shopBalances);
        $topCreditShopIds = array_slice(array_keys($shopBalances), 0, 5, true);

        foreach ($topCreditShopIds as $busId) {
            $business = AdCustomerHasBusiness::find($busId);
            if (!$business) continue;
            $agentRec = AdAgent::find($business->agent_id);
            $daysOutstanding = max(0, (int) round(abs($endDate->diffInDays(Carbon::parse($shopBalances[$busId]['oldest_date'])))));

            $termsDays = $business->payment_terms_days ?: 30;
            $status = 'Within term';
            if ($daysOutstanding > $termsDays) {
                $status = 'Overdue';
            } elseif ($business->credit_limit > 0 && $shopBalances[$busId]['balance'] >= ($business->credit_limit * 0.8)) {
                $status = 'Near limit';
            }

            $topCreditShops[] = [
                'outlet_name' => $business->business_name ?? ('Outlet #' . $busId),
                'agent_name' => $agentRec ? $agentRec->agent_name : 'N/A',
                'balance' => $shopBalances[$busId]['balance'],
                'days_outstanding' => $daysOutstanding,
                'status' => $status,
            ];
        }

        // ============================================================
        // Top 5 Highest Sale Retail Shops (current month)
        // ============================================================
        $allMonthInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $allAssignedCustomerIdsUnique)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $shopSales = [];
        foreach ($allMonthInvoices as $inv) {
            $busId = $inv->ad_customer_has_business_id;
            if (!isset($shopSales[$busId])) {
                $shopSales[$busId] = 0;
            }
            $shopSales[$busId] += $inv->invoice_price;
        }

        // Previous month sales per shop
        $prevMonthInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $allAssignedCustomerIdsUnique)
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->get();

        $shopSalesPrev = [];
        foreach ($prevMonthInvoices as $inv) {
            $busId = $inv->ad_customer_has_business_id;
            if (!isset($shopSalesPrev[$busId])) {
                $shopSalesPrev[$busId] = 0;
            }
            $shopSalesPrev[$busId] += $inv->invoice_price;
        }

        arsort($shopSales);
        $topSaleShopIds = array_slice(array_keys($shopSales), 0, 5, true);
        $topSaleShops = [];

        foreach ($topSaleShopIds as $busId) {
            $business = AdCustomerHasBusiness::find($busId);
            if (!$business) continue;
            $agentRec = AdAgent::find($business->agent_id);
            $prevSale = $shopSalesPrev[$busId] ?? 0;
            $currentSale = $shopSales[$busId];
            $vsLastMonth = $prevSale > 0 ? round((($currentSale - $prevSale) / $prevSale) * 100, 1) : ($currentSale > 0 ? 100 : 0);

            $topSaleShops[] = [
                'outlet_name' => $business->business_name ?? ('Outlet #' . $busId),
                'agent_name' => $agentRec ? $agentRec->agent_name : 'N/A',
                'sales' => $currentSale,
                'vs_last_month' => $vsLastMonth,
            ];
        }

        // ============================================================
        // Top 5 Fast Moving SKUs
        // ============================================================
        $allMonthInvoiceIds = $allMonthInvoices->pluck('id')->toArray();
        $totalBranchSales = $allMonthInvoices->sum('invoice_price');

        $skuSales = AdCubusinessHasProductItem::whereIn('ad_cubusiness_has_invoice_id', $allMonthInvoiceIds)
            ->select('pm_product_item_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('pm_product_item_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // Calculate working days in the month (Mon-Sat, 6 days/week)
        $workingDays = 0;
        $tempDate = $startDate->copy();
        while ($tempDate->lte($endDate)) {
            if ($tempDate->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays++;
            }
            $tempDate->addDay();
        }
        if ($workingDays === 0) $workingDays = 1;

        $topSkus = [];
        foreach ($skuSales as $sku) {
            $productItem = PmProductItem::find($sku->pm_product_item_id);
            $skuName = $productItem ? $productItem->product_name : ('SKU #' . $sku->pm_product_item_id);
            $branchPct = $totalBranchSales > 0 ? round(($sku->total_sales / $totalBranchSales) * 100, 1) : 0;

            $topSkus[] = [
                'sku_name' => $skuName,
                'units_sold' => $sku->total_qty,
                'units_per_day' => round($sku->total_qty / $workingDays),
                'sales' => $sku->total_sales,
                'branch_pct' => $branchPct,
            ];
        }

        // ============================================================
        // Top 5 Highest Sales Growth Retail Shops (month over month)
        // ============================================================
        $topGrowthShops = [];
        $growthData = [];

        foreach ($shopSales as $busId => $currentSale) {
            $prevSale = $shopSalesPrev[$busId] ?? 0;
            if ($prevSale > 0) {
                $growthRs = $currentSale - $prevSale;
                $growthPct = round(($growthRs / $prevSale) * 100, 1);
                $growthData[$busId] = [
                    'current' => $currentSale,
                    'prev' => $prevSale,
                    'growth_rs' => $growthRs,
                    'growth_pct' => $growthPct,
                ];
            }
        }

        // Sort by growth Rs desc (ranked by growth in Rs.)
        uasort($growthData, function ($a, $b) {
            return $b['growth_rs'] <=> $a['growth_rs'];
        });
        $topGrowthIds = array_slice(array_keys($growthData), 0, 5, true);

        $prevMonthLabel = $prevStartDate->format('M');
        $currentMonthLabel = $startDate->format('M');

        foreach ($topGrowthIds as $busId) {
            $business = AdCustomerHasBusiness::find($busId);
            if (!$business) continue;
            $agentRec = AdAgent::find($business->agent_id);
            $gd = $growthData[$busId];

            $topGrowthShops[] = [
                'outlet_name' => $business->business_name ?? ('Outlet #' . $busId),
                'agent_name' => $agentRec ? $agentRec->agent_name : 'N/A',
                'prev_sales' => $gd['prev'],
                'current_sales' => $gd['current'],
                'growth_rs' => $gd['growth_rs'],
                'growth_pct' => $gd['growth_pct'],
            ];
        }

        return [
            'leaderboard' => $leaderboard,
            'agent_credit_summary' => $agentCreditSummary,
            'top_credit_shops' => $topCreditShops,
            'outlet_movement' => [
                'opening' => $totalActiveOpening,
                'new_shops' => $totalNewShops,
                'newly_dormant' => $totalDormant,
                'closing' => $closingActive,
                'growth_pct' => $activeGrowth,
            ],
            'top_sale_shops' => $topSaleShops,
            'top_skus' => $topSkus,
            'top_growth_shops' => $topGrowthShops,
            'prev_month_label' => $prevMonthLabel,
            'current_month_label' => $currentMonthLabel,
            'return_trend' => $months,
            'credit_ageing' => $creditAgeing,
            'next_targets' => [
                'label' => $nextMonthDate->format('M'),
                'targets' => $nextMonthTargets,
            ],
        ];
    }

    public function getAllAgentPerformanceData(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $data = $this->_buildAllAgentPerformanceData($request);

        return response()->json(array_merge(['success' => true], $data));
    }

    public function exportAllAgentPerformancePdf(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $data = $this->_buildAllAgentPerformanceData($request);
        $monthYear = Carbon::createFromFormat('Y-m', $request->input('month'))->format('F Y');

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $pdf = Pdf::loadView('reports.exports.pdf.allAgentPerformance', [
            'data' => $data,
            'monthYear' => $monthYear,
            'companyInfo' => $companyInfo,
        ])->setPaper('a4', 'landscape');

        $fileName = 'All_Agent_Performance_'.$request->input('month').'.pdf';

        return $pdf->download($fileName);
    }

    /**
     * Show the Agent Order Requests Report view.
     */
    public function agentOrderRequestsIndex()
    {
        return view('reports.agentOrderRequests');
    }

    /**
     * Fetch summary data for all agents (total orders, paid, outstanding).
     */
    public function getAgentOrderRequestsData(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $date = $request->input('date');
        $startDate = $date
            ? Carbon::parse($date)->startOfDay()
            : ($request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null);
        $endDate = $date
            ? Carbon::parse($date)->endOfDay()
            : ($request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null);

        $agents = AdAgent::where('status', 1)->get();
        $reportData = [];

        foreach ($agents as $agent) {
            $query = \App\Models\StmOrderRequest::where('agent_id', $agent->id)->whereNot('status', 2);

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $orders = $query->get();

            $totalOrderAmount = $orders->sum('grand_total');
            $paidAmount = $orders->sum('paid_amount');
            $outstanding = $totalOrderAmount - $paidAmount;

            $reportData[] = [
                'agent_id' => $agent->id,
                'agent_name' => $agent->agent_name,
                'agent_code' => $agent->agent_code,
                'total_orders' => $orders->count(),
                'total_order_amount' => $totalOrderAmount,
                'paid_amount' => $paidAmount,
                'outstanding' => $outstanding,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $reportData,
        ]);
    }

    /**
     * Fetch order requests and payment details for a specific agent.
     */
    public function getAgentOrderRequestDetails(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $agentId = $request->input('agent_id');
        $date = $request->input('date');
        $startDate = $date
            ? Carbon::parse($date)->startOfDay()
            : ($request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null);
        $endDate = $date
            ? Carbon::parse($date)->endOfDay()
            : ($request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null);

        $query = \App\Models\StmOrderRequest::with(['payments'])
            ->where('agent_id', $agentId)->whereNot('status', 2);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $ordersData = [];
        $paymentsData = [];

        $statusMap = [
            0 => 'Pending Approval',
            1 => 'Approved',
            2 => 'Rejected',
            3 => 'In Production',
            4 => 'Ready to Dispatch',
            5 => 'Dispatch Completed',
            6 => 'Dispatch Confirmed',
            7 => 'Completed',
        ];

        $paymentStatusMap = [
            1 => 'Pending',
            2 => 'Active',
        ];

        foreach ($orders as $order) {
            $orderGrandTotal = (float) $order->grand_total;
            $orderPaid = (float) $order->paid_amount;
            $orderOutstanding = $orderGrandTotal - $orderPaid;

            $ordersData[] = [
                'id' => $order->id,
                'order_number' => $order->order_number ?? 'ORD-'.$order->id,
                'delivery_date' => $order->delivery_date ? Carbon::parse($order->delivery_date)->format('Y-m-d') : 'N/A',
                'grand_total' => $orderGrandTotal,
                'paid_amount' => $orderPaid,
                'outstanding' => $orderOutstanding,
                'status' => $statusMap[$order->status] ?? 'Unknown',
                'status_code' => $order->status,
                'created_at' => Carbon::parse($order->created_at)->format('Y-m-d'),
            ];

        }

        // Fetch Agent Payments directly
        $agentPaymentsQuery = \App\Models\AdAgentPayment::where('agent_id', $agentId);

        if ($startDate && $endDate) {
            $agentPaymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }

        $agentPayments = $agentPaymentsQuery->orderBy('payment_date', 'desc')->get();

        $agentPaymentMethodMap = [
            1 => 'Cash',
            2 => 'Card',
            3 => 'Bank Transfer',
            4 => 'Credit Note',
        ];

        $agentPaymentStatusMap = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
        ];

        foreach ($agentPayments as $payment) {
            $paymentsData[] = [
                'id' => $payment->id,
                'payment_number' => 'PAY-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                'payment_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('Y-m-d') : 'N/A',
                'payment_amount' => (float) $payment->amount,
                'payment_method' => $agentPaymentMethodMap[$payment->payment_method] ?? 'Other',
                'payment_reference' => $payment->rejection_reason ?? '-', // Use rejection_reason as reference or empty if not present, as reference is not there. Or maybe we can just pass empty
                'status' => $agentPaymentStatusMap[$payment->status] ?? 'Unknown',
                'status_code' => $payment->status,
                'notes' => $payment->rejection_reason ?? '-',
            ];
        }

        return response()->json([
            'success' => true,
            'orders' => $ordersData,
            'payments' => $paymentsData,
        ]);
    }

    /**
     * Export Agent Order Requests details to Excel.
     */
    public function exportAgentOrderRequestsExcel(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $agentId = $request->input('agent_id');
        $date = $request->input('date');
        $startDate = $date
            ? Carbon::parse($date)->startOfDay()
            : ($request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null);
        $endDate = $date
            ? Carbon::parse($date)->endOfDay()
            : ($request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null);

        $agent = AdAgent::findOrFail($agentId);

        $query = \App\Models\StmOrderRequest::with(['payments'])->where('agent_id', $agentId)->whereNot('status', 2);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $ordersData = [];

        $statusMap = [
            0 => 'Pending Approval',
            1 => 'Approved',
            2 => 'Rejected',
            3 => 'In Production',
            4 => 'Ready to Dispatch',
            5 => 'Dispatch Completed',
            6 => 'Dispatch Confirmed',
            7 => 'Completed',
        ];

        $totalOrderAmount = 0;
        $totalPaidAmount = 0;
        $totalOutstanding = 0;

        foreach ($orders as $order) {
            $orderGrandTotal = (float) $order->grand_total;
            $orderPaid = (float) $order->paid_amount;
            $orderOutstanding = $orderGrandTotal - $orderPaid;

            $totalOrderAmount += $orderGrandTotal;
            $totalPaidAmount += $orderPaid;
            $totalOutstanding += $orderOutstanding;

            $ordersData[] = [
                'id' => $order->id,
                'order_number' => $order->order_number ?? 'ORD-'.$order->id,
                'delivery_date' => $order->delivery_date ? Carbon::parse($order->delivery_date)->format('Y-m-d') : 'N/A',
                'grand_total' => $orderGrandTotal,
                'paid_amount' => $orderPaid,
                'outstanding' => $orderOutstanding,
                'status' => $statusMap[$order->status] ?? 'Unknown',
            ];
        }

        // Fetch Agent Payments directly
        $agentPaymentsQuery = \App\Models\AdAgentPayment::where('agent_id', $agentId);

        if ($startDate && $endDate) {
            $agentPaymentsQuery->whereBetween('payment_date', [$startDate, $endDate]);
        }

        $agentPayments = $agentPaymentsQuery->orderBy('payment_date', 'desc')->get();

        $agentPaymentMethodMap = [
            1 => 'Cash',
            2 => 'Card',
            3 => 'Bank Transfer',
            4 => 'Credit Note',
        ];

        $agentPaymentStatusMap = [
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
        ];

        $paymentsData = [];
        $totalPaymentAmount = 0;

        foreach ($agentPayments as $payment) {
            $paymentAmt = (float) $payment->amount;
            $totalPaymentAmount += $paymentAmt;

            $paymentsData[] = [
                'id' => $payment->id,
                'payment_number' => 'PAY-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                'payment_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('Y-m-d') : 'N/A',
                'payment_amount' => $paymentAmt,
                'payment_method' => $agentPaymentMethodMap[$payment->payment_method] ?? 'Other',
                'payment_reference' => $payment->rejection_reason ?? '-',
                'status' => $agentPaymentStatusMap[$payment->status] ?? 'Unknown',
            ];
        }

        $selectedDate = $date ?? ($startDate ? $startDate->format('Y-m-d') : null);
        $dateRange = ($startDate && $endDate)
            ? ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')
                ? $startDate->format('Y-m-d')
                : $startDate->format('Y-m-d').' to '.$endDate->format('Y-m-d'))
            : 'All Time';

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $viewData = [
            'agent' => $agent,
            'orders' => $ordersData,
            'payments' => $paymentsData,
            'summary' => [
                'total_order_amount' => $totalOrderAmount,
                'total_paid_amount' => $totalPaidAmount,
                'total_outstanding' => $totalOutstanding,
                'total_payment_amount' => $totalPaymentAmount,
            ],
            'selectedDate' => $selectedDate,
            'dateRange' => $dateRange,
            'companyInfo' => $companyInfo,
        ];

        $fileName = 'Agent_Order_Requests_'.($startDate ? $startDate->format('Y-m-d') : 'AllTime');

        return response(view('reports.exports.excel.agentOrderRequests', $viewData))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'.xls"');
    }

    /**
     * Export All Agents Order Requests summary to Excel.
     */
    public function exportAllAgentOrderRequestsExcel(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $date = $request->input('date');
        $startDate = $date
            ? Carbon::parse($date)->startOfDay()
            : ($request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null);
        $endDate = $date
            ? Carbon::parse($date)->endOfDay()
            : ($request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null);

        $agents = AdAgent::where('status', 1)->get();
        $reportData = [];

        $totalOrdersSum = 0;
        $totalOrderAmountSum = 0;
        $paidAmountSum = 0;
        $outstandingSum = 0;

        foreach ($agents as $agent) {
            $query = \App\Models\StmOrderRequest::where('agent_id', $agent->id)->whereNot('status', 2);

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $orders = $query->get();

            $totalOrderAmount = $orders->sum('grand_total');
            $paidAmount = $orders->sum('paid_amount');
            $outstanding = $totalOrderAmount - $paidAmount;

            $totalOrdersSum += $orders->count();
            $totalOrderAmountSum += $totalOrderAmount;
            $paidAmountSum += $paidAmount;
            $outstandingSum += $outstanding;

            $reportData[] = [
                'agent_id' => $agent->id,
                'agent_name' => $agent->agent_name,
                'agent_code' => $agent->agent_code,
                'total_orders' => $orders->count(),
                'total_order_amount' => $totalOrderAmount,
                'paid_amount' => $paidAmount,
                'outstanding' => $outstanding,
            ];
        }

        $selectedDate = $date ?? ($startDate ? $startDate->format('Y-m-d') : null);
        $dateRange = ($startDate && $endDate)
            ? ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')
                ? $startDate->format('Y-m-d')
                : $startDate->format('Y-m-d').' to '.$endDate->format('Y-m-d'))
            : 'All Time';

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $viewData = [
            'reportData' => $reportData,
            'summary' => [
                'total_orders' => $totalOrdersSum,
                'total_order_amount' => $totalOrderAmountSum,
                'paid_amount' => $paidAmountSum,
                'outstanding' => $outstandingSum,
            ],
            'selectedDate' => $selectedDate,
            'dateRange' => $dateRange,
            'companyInfo' => $companyInfo,
        ];

        $fileName = 'Agent_Order_Requests_Summary_'.($startDate ? $startDate->format('Y-m-d') : 'AllTime');

        return response(view('reports.exports.excel.allAgentOrderRequests', $viewData))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="'.$fileName.'.xls"');
    }

    public function skuTargetVsActualIndex()
    {
        $agents = AdAgent::where('status', 1)->get(['id', 'agent_name', 'agent_code']);
        return view('reports.skuTargetVsActual', compact('agents'));
    }

    private function _buildSkuTargetVsActualData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $agentId = (int) $request->input('agent_id');
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        $agent = AdAgent::find($agentId);
        $targetYear = $endDate->year;
        $targetMonth = $endDate->month;

        // Monthly Target
        $monthlyTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
            ->where('target_year', $targetYear)
            ->where('target_month', $targetMonth)
            ->first();
        $monthlyTargetId = $monthlyTargetRec ? $monthlyTargetRec->id : null;

        // Days prorate calculation
        $daysInMonth = $startDate->daysInMonth; // e.g. 31
        $selectedDays = max(1, (int) round(abs($endDate->diffInDays($startDate))) + 1);
        $prorateRatio = min(1.0, $selectedDays / $daysInMonth);

        // Invoices for this agent
        $customerIds = AdCustomerHasBusiness::where('agent_id', $agentId)->pluck('id')->toArray();
        $invoiceIds = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $customerIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->pluck('id')
            ->toArray();

        // Actual sold quantities
        $soldQuantities = AdCubusinessHasProductItem::whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
            ->selectRaw('pm_product_item_id, SUM(quantity) as actual_qty')
            ->groupBy('pm_product_item_id')
            ->pluck('actual_qty', 'pm_product_item_id')
            ->toArray();

        // Item targets from DB for this agent and monthly target
        $itemTargetsMap = [];
        if ($monthlyTargetId) {
            $itemTargets = AdAgentHasItemTargets::where('monthly_target_id', $monthlyTargetId)->get();
            foreach ($itemTargets as $it) {
                if ((float) $it->target_qty > 0) {
                    $itemTargetsMap[$it->pm_product_item_id] = (float) $it->target_qty;
                }
            }
        }

        // Categories in specific bakery order: Bread (5), Bun (4), Cake (6), Rusk & Cookies (7)
        $categories = PmProductCategory::whereIn('id', [5, 4, 6, 7])
            ->orderByRaw("FIELD(id, 5, 4, 6, 7)")
            ->get();

        $categoryData = [];
        $grandTarget = 0;
        $grandActual = 0;

        foreach ($categories as $cat) {
            $cName = $cat->category_name;
            if ($cName === 'Bun') $displayName = 'Bun Category';
            elseif ($cName === 'Rusk and Cookies') $displayName = 'Rusk & Cookies';
            else $displayName = $cName;

            $catItems = PmProductItem::where('pm_product_category_id', $cat->id)
                ->where('status', 1)
                ->get();

            $itemsList = [];
            $subTarget = 0;
            $subActual = 0;
            $subHasTargetCount = 0;

            foreach ($catItems as $p) {
                $pId = $p->id;
                $cleanName = trim(rtrim($p->product_name, '.'));
                $displayPName = ucwords(strtolower($cleanName));
                if (stripos($displayPName, 'Sandwitch') !== false || stripos($displayPName, 'Sandwich') !== false) {
                    $displayPName = 'Sandwitch Bread 400g';
                }

                $hasTarget = isset($itemTargetsMap[$pId]) && (float) $itemTargetsMap[$pId] > 0;
                $actual = (int) ($soldQuantities[$pId] ?? 0);

                if ($hasTarget) {
                    $mTarget = (float) $itemTargetsMap[$pId];
                    $proratedTarget = (int) round($mTarget * $prorateRatio);
                    $gap = $actual - $proratedTarget;
                    $achievement = $proratedTarget > 0 ? round(($actual / $proratedTarget) * 100) : 0;
                    $isAchieved = ($gap >= 0);
                    $subTarget += $proratedTarget;
                    $subHasTargetCount++;
                } else {
                    $proratedTarget = null;
                    $gap = null;
                    $achievement = null;
                    $isAchieved = null;
                }

                $itemsList[] = [
                    'id' => $pId,
                    'name' => $displayPName,
                    'has_target' => $hasTarget,
                    'target' => $proratedTarget,
                    'actual' => $actual,
                    'achievement' => $achievement,
                    'gap' => $gap,
                    'is_achieved' => $isAchieved,
                ];

                $subActual += $actual;
            }

            usort($itemsList, fn($a, $b) => strcmp($a['name'], $b['name']));

            $subHasTarget = ($subHasTargetCount > 0);
            $subGap = $subHasTarget ? ($subActual - $subTarget) : null;
            $subAch = ($subHasTarget && $subTarget > 0) ? round(($subActual / $subTarget) * 100) : null;

            $categoryData[] = [
                'category_id' => $cat->id,
                'category_name' => $displayName,
                'items' => $itemsList,
                'has_target' => $subHasTarget,
                'subtotal_target' => $subTarget,
                'subtotal_actual' => $subActual,
                'subtotal_achievement' => $subAch,
                'subtotal_gap' => $subGap,
                'is_achieved' => ($subGap !== null ? ($subGap >= 0) : null),
            ];

            $grandTarget += $subTarget;
            $grandActual += $subActual;
        }

        $grandHasTarget = ($grandTarget > 0);
        $grandGap = $grandHasTarget ? ($grandActual - $grandTarget) : null;
        $grandAch = ($grandHasTarget && $grandTarget > 0) ? round(($grandActual / $grandTarget) * 100) : null;

        $sFormatted = $startDate->format('d M Y');
        $eFormatted = $endDate->format('d M Y');

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;
        $companyName = $companyInfo['business_name'] ?? 'Dimuthu Bake House';

        return [
            'agent' => [
                'id' => $agent->id ?? $agentId,
                'name' => $agent->agent_name ?? 'Unknown',
                'code' => $agent->agent_code ?? 'N/A',
            ],
            'header_info' => [
                'company_name' => $companyName,
                'agent_label' => ($agent->agent_name ?? 'Unknown') . ' (' . ($agent->agent_code ?? 'N/A') . ')',
                'date_range_label' => "{$sFormatted} – {$eFormatted}",
                'subtitle' => "{$companyName} • " . ($agent->agent_name ?? 'Unknown') . " (" . ($agent->agent_code ?? 'N/A') . ") • {$sFormatted} – {$eFormatted}",
                'from_date' => $sFormatted,
                'to_date' => $eFormatted,
                'from_raw' => $startDate->format('Y-m-d'),
                'to_raw' => $endDate->format('Y-m-d'),
                'generated_at' => Carbon::now()->format('d M Y, h:i A'),
            ],
            'categories' => $categoryData,
            'totals' => [
                'target' => $grandTarget,
                'actual' => $grandActual,
                'achievement' => $grandAch,
                'gap' => $grandGap,
                'is_achieved' => ($grandGap >= 0),
            ],
            'proration' => [
                'selected_days' => $selectedDays,
                'days_in_month' => $daysInMonth,
                'ratio' => $prorateRatio,
            ],
        ];
    }

    public function getSkuTargetVsActualData(Request $request)
    {
        $data = $this->_buildSkuTargetVsActualData($request);
        return response()->json(array_merge(['success' => true], $data));
    }

    public function exportSkuTargetVsActualPdf(Request $request)
    {
        $data = $this->_buildSkuTargetVsActualData($request);

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $pdf = Pdf::loadView('reports.exports.pdf.skuTargetVsActual', [
            'data' => $data,
            'companyInfo' => $companyInfo,
        ])->setPaper('a4', 'portrait');

        $fileName = 'SKU_Target_vs_Actual_' . ($data['agent']['name'] ?? 'Agent') . '_' . $request->input('start_date') . '.pdf';
        return $pdf->download($fileName);
    }
}
