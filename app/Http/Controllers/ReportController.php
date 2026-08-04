<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdAgent;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdCubusinessHasInvoice;
use App\Models\AdInvoice;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AdAgentMonthlyTarget;
use App\Models\AdRoute;
use App\Models\AdCubusinessHasReturnProductItem;
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
            ]
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
                     : $data['startDate'] . ' to ' . $data['endDate'];

        $configPath = public_path('system_config.json');
        $companyInfo = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : null;

        $viewData = [
            'reportData' => $data['reportData'],
            'agentName' => $agentName,
            'dateRange' => $dateRange,
            'companyInfo' => $companyInfo,
        ];

        $fileName = 'Agent_Sales_Report_' . $data['startDate'];

        if ($request->type === 'pdf') {
            $pdf = Pdf::loadView('reports.exports.pdf.agentShopSales', $viewData)
                      ->setPaper('a4', 'landscape');
            return $pdf->download($fileName . '.pdf');
        } else {
            return response(view('reports.exports.excel.agentShopSales', $viewData))
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '.xls"');
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
                    if (!isset($salesProducts[$prodName])) {
                        $salesProducts[$prodName] = [
                            'name' => $prodName,
                            'quantity' => 0,
                            'total_price' => 0
                        ];
                    }
                    $salesProducts[$prodName]['quantity'] += $item->quantity;
                    $salesProducts[$prodName]['total_price'] += $item->total_price;
                }
            }

            foreach ($invoice->returnItems as $returnItem) {
                if ($returnItem->product) {
                    $prodName = $returnItem->product->product_name ?? 'Unknown Product';
                    if (!isset($returnProducts[$prodName])) {
                        $returnProducts[$prodName] = [
                            'name' => $prodName,
                            'quantity' => 0,
                            'total_price' => 0
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
            'return_products' => array_values($returnProducts)
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
            'endDate' => $endDate->toDateString()
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

        $agentId = $request->input('agent_id');
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
        $targetYear = $endDate->year;
        $targetMonth = $endDate->month;

        $agent = AdAgent::find($agentId);
        
        // Routes
        $routes = AdRoute::where('agent_id', $agentId)->pluck('route_name')->toArray();
        $routesString = empty($routes) ? 'No assigned routes' : implode(', ', $routes);

        // Monthly Target
        $monthlyTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
            ->where('target_year', $targetYear)
            ->where('target_month', $targetMonth)
            ->first();
        $monthlyTargetVal = $monthlyTargetRec ? $monthlyTargetRec->monthly_sales_target : 0;

        // All assigned customers for this agent
        $assignedCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->get();
        $assignedCustomerIds = $assignedCustomers->pluck('id')->toArray();
        $totalOutlets = count($assignedCustomerIds);

        // Invoices within date range
        $invoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        // Weekly Sales
        $weeklySales = $invoices->sum('invoice_price');
        $totalReturns = $invoices->sum('return_price');
        
        // Visit Compliance
        $uniqueVisited = $invoices->pluck('ad_customer_has_business_id')->unique()->count();
        $visitCompliance = $totalOutlets > 0 ? round(($uniqueVisited / $totalOutlets) * 100) : 0;
        
        // Return %
        $returnPercent = $weeklySales > 0 ? round(($totalReturns / $weeklySales) * 100, 1) : 0;

        // MTD Sales
        $startOfMonth = $endDate->copy()->startOfMonth();
        $mtdInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$startOfMonth, $endDate])
            ->get();
        $mtdSales = $mtdInvoices->sum('invoice_price');
        $remainingTarget = max(0, $monthlyTargetVal - $mtdSales);
        
        // Daily Outlet Visits
        $dailyVisitsRaw = $invoices->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m-d');
        });
        
        $dailyOutletVisits = [];
        $currentDate = $startDate->copy();
        while($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dayInvoices = $dailyVisitsRaw->get($dateStr, collect());
            $outletsVisited = $dayInvoices->pluck('ad_customer_has_business_id')->unique()->count();
            
            $dailyOutletVisits[] = [
                'day' => $currentDate->format('D d M'),
                'date' => $dateStr,
                'outlets_visited' => $outletsVisited,
                'status' => $outletsVisited >= 30 ? 'OK' : 'Below 30'
            ];
            $currentDate->addDay();
        }

        // Returns By Reason
        $invoiceIds = $invoices->pluck('id')->toArray();
        $returnItems = AdCubusinessHasReturnProductItem::with('product')
            ->whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
            ->get();
            
        $returnsByReasonRaw = $returnItems->groupBy('reason');
        $returnsByReason = [];
        foreach($returnsByReasonRaw as $reason => $items) {
            $val = $items->sum('total_price');
            $pct = $weeklySales > 0 ? round(($val / $weeklySales) * 100, 1) : 0;
            $returnsByReason[] = [
                'reason' => $reason ?: 'Unknown',
                'value' => $val,
                'percent' => $pct
            ];
        }
        
        usort($returnsByReason, function($a, $b) {
            return $b['value'] <=> $a['value'];
        });

        // Top returned product
        $topReturnProduct = 'None';
        $topReturnQty = 0;
        $productGroups = $returnItems->groupBy('pm_product_item_id');
        foreach($productGroups as $items) {
            $qty = $items->sum('return_quantity');
            if($qty > $topReturnQty) {
                $topReturnQty = $qty;
                $topReturnProduct = $items->first()->product->product_name ?? 'Unknown';
            }
        }

        // Credit & Collections
        $creditSalesThisWeek = $invoices->sum(function($inv) {
            return max(0, $inv->net_price - $inv->total_amount_paid);
        });
        
        $collectionsThisWeek = $invoices->sum('total_amount_paid'); 
        $collectionRate = $weeklySales > 0 ? round(($collectionsThisWeek / $weeklySales) * 100) : 0;
        
        // Closing dues (all time outstanding)
        $allInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->where('created_at', '<=', $endDate)
            ->get();
        $closingDues = $allInvoices->sum(function($inv) {
            return max(0, $inv->net_price - $inv->total_amount_paid);
        });

        // Aged 30+ days
        $thirtyDaysAgo = $endDate->copy()->subDays(30);
        $agedInvoices = $allInvoices->where('created_at', '<=', $thirtyDaysAgo);
        $aged30Days = $agedInvoices->sum(function($inv) {
            return max(0, $inv->net_price - $inv->total_amount_paid);
        });

        // Credit Utilization
        $totalCreditLimit = $assignedCustomers->sum('credit_limit');
        $creditUtilization = $totalCreditLimit > 0 ? round(($closingDues / $totalCreditLimit) * 100) : 0;

        // Outlet Growth
        $newShopsAdded = AdCustomerHasBusiness::where('agent_id', $agentId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
            
        $fourteenDaysAgo = $endDate->copy()->subDays(14);
        
        // To find dormant shops, find customers who don't have an invoice in the last 14 days
        $activeInLast14DaysIds = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$fourteenDaysAgo, $endDate])
            ->pluck('ad_customer_has_business_id')
            ->unique()
            ->toArray();
            
        $activeOutlets = count($activeInLast14DaysIds);
        $dormantShops = max(0, $totalOutlets - $activeOutlets);

        return [
            'success' => true,
            'agent' => [
                'name' => $agent->agent_name ?? 'Unknown',
                'code' => $agent->agent_code ?? 'N/A',
                'routes' => $routesString,
            ],
            'monthly_target' => $monthlyTargetVal,
            'weekly_sales' => $weeklySales,
            'visit_compliance' => [
                'percent' => $visitCompliance,
                'visited' => $uniqueVisited,
                'total' => $totalOutlets
            ],
            'returns' => [
                'percent' => $returnPercent,
                'total_returns' => $totalReturns,
            ],
            'credit_utilization' => [
                'percent' => $creditUtilization,
                'used' => $closingDues,
                'limit' => $totalCreditLimit
            ],
            'target_progress' => [
                'remaining' => $remainingTarget,
                'mtd_sales' => $mtdSales,
                'target' => $monthlyTargetVal
            ],
            'daily_visits' => $dailyOutletVisits,
            'returns_by_reason' => $returnsByReason,
            'top_return_product' => [
                'name' => $topReturnProduct,
                'qty' => $topReturnQty
            ],
            'credit_collections' => [
                'credit_sales' => $creditSalesThisWeek,
                'collections' => $collectionsThisWeek,
                'collection_rate' => $collectionRate,
                'closing_dues' => $closingDues,
                'aged_30_days' => $aged30Days
            ],
            'outlet_growth' => [
                'new_shops' => $newShopsAdded,
                'dormant_shops' => $dormantShops,
                'active_outlets' => $activeOutlets,
                'total_outlets' => $totalOutlets
            ]
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
            'companyInfo' => $companyInfo
        ])->setPaper('a4', 'portrait');

        $fileName = 'Weekly_Agent_Review_' . $data['agent']['name'] . '_' . $request->input('start_date') . '.pdf';
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

        $agent = AdAgent::find($agentId);
        $routes = AdRoute::where('agent_id', $agentId)->pluck('route_name')->toArray();
        $routesString = empty($routes) ? 'No assigned routes' : implode(', ', $routes);

        // Monthly Target
        $monthlyTargetRec = AdAgentMonthlyTarget::where('agent_id', $agentId)
            ->where('target_year', $targetYear)
            ->where('target_month', $targetMonth)
            ->first();
        $monthlyTargetVal = $monthlyTargetRec ? $monthlyTargetRec->monthly_sales_target : 0;
        
        $invoicingCommissionRate = $monthlyTargetRec ? $monthlyTargetRec->invoicing_commission_rate : 15.0; 
        $targetCommissionRate = $monthlyTargetRec ? $monthlyTargetRec->target_commission_rate : 5.0;

        // All assigned customers for this agent
        $assignedCustomers = AdCustomerHasBusiness::where('agent_id', $agentId)->get();
        $assignedCustomerIds = $assignedCustomers->pluck('id')->toArray();
        $totalOutlets = count($assignedCustomerIds);

        // Current Month Invoices
        $invoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        // Previous Month Invoices
        $prevInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->get();

        // MoM Sales
        $monthlySales = $invoices->sum('invoice_price');
        $prevMonthlySales = $prevInvoices->sum('invoice_price');
        $salesGrowth = $prevMonthlySales > 0 ? round((($monthlySales - $prevMonthlySales) / $prevMonthlySales) * 100, 1) : 0;
        
        // Target calculations
        $remainingTarget = max(0, $monthlyTargetVal - $monthlySales);
        $targetProgressPercent = $monthlyTargetVal > 0 ? round(($monthlySales / $monthlyTargetVal) * 100) : 0;

        // Returns MoM
        $totalReturns = $invoices->sum('return_price');
        $prevTotalReturns = $prevInvoices->sum('return_price');
        $returnPercent = $monthlySales > 0 ? round(($totalReturns / $monthlySales) * 100, 1) : 0;
        $prevReturnPercent = $prevMonthlySales > 0 ? round(($prevTotalReturns / $prevMonthlySales) * 100, 1) : 0;

        // Collections MoM
        $totalCollections = $invoices->sum('total_amount_paid');
        $prevTotalCollections = $prevInvoices->sum('total_amount_paid');
        $collectionPercent = $monthlySales > 0 ? round(($totalCollections / $monthlySales) * 100) : 0;
        $prevCollectionPercent = $prevMonthlySales > 0 ? round(($prevTotalCollections / $prevMonthlySales) * 100) : 0;

        // Outlet Growth MoM
        $newShopsCurrent = AdCustomerHasBusiness::where('agent_id', $agentId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $newShopsPrev = AdCustomerHasBusiness::where('agent_id', $agentId)
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->count();
            
        // Dormant Shops Calculation
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
        
        // Week-by-Week Breakdown
        $weeklyDataRaw = $invoices->groupBy(function($inv) {
            return Carbon::parse($inv->created_at)->format('W');
        });
        
        $weekByWeek = [];
        foreach($weeklyDataRaw as $week => $weekInvoices) {
            $weekStart = Carbon::now()->setISODate($targetYear, $week)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            
            if ($weekStart < $startDate) $weekStart = $startDate->copy();
            if ($weekEnd > $endDate) $weekEnd = $endDate->copy();
            
            $weekLabel = "Week $week (" . $weekStart->format('d M') . '–' . $weekEnd->format('d M') . ')';
            
            $wSales = $weekInvoices->sum('invoice_price');
            $wReturns = $weekInvoices->sum('return_price');
            $wRetPct = $wSales > 0 ? round(($wReturns / $wSales) * 100, 1) : 0;
            
            $wVisited = $weekInvoices->pluck('ad_customer_has_business_id')->unique()->count();
            $wVisitComp = $totalOutlets > 0 ? round(($wVisited / $totalOutlets) * 100) : 0;
            
            $weekByWeek[] = [
                'label' => $weekLabel,
                'sales' => $wSales,
                'visit_compliance' => $wVisitComp,
                'return_percent' => $wRetPct,
                'week' => (int)$week
            ];
        }
        
        usort($weekByWeek, function($a, $b) {
            return $a['week'] <=> $b['week'];
        });

        // Policy-Violation Returns
        $invoiceIds = $invoices->pluck('id')->toArray();
        $acceptableReasons = ['Early fungus', 'Damaged bag (packing)', 'Deformed product'];
        $policyViolationItems = AdCubusinessHasReturnProductItem::with('invoice.business')
            ->whereIn('ad_cubusiness_has_invoice_id', $invoiceIds)
            ->whereNotIn('reason', $acceptableReasons)
            ->get();
            
        $policyReturns = [];
        foreach($policyViolationItems as $item) {
            $policyReturns[] = [
                'date' => Carbon::parse($item->created_at)->format('d M'),
                'outlet' => $item->invoice && $item->invoice->business ? ($item->invoice->business->business_name ?? 'Unknown') : 'Unknown',
                'value' => $item->total_price,
                'remark' => $item->reason
            ];
        }

        // Commission
        $netSales = max(0, $monthlySales - $totalReturns);
        $invoicingCommissionAmount = $netSales * ($invoicingCommissionRate / 100);
        $targetBonusAmount = 0;
        if ($monthlySales >= $monthlyTargetVal && $monthlyTargetVal > 0) {
            $targetBonusAmount = $netSales * ($targetCommissionRate / 100);
        }
        $totalPayable = $invoicingCommissionAmount + $targetBonusAmount;

        // Credit Position (Ageing)
        $allInvoices = AdCubusinessHasInvoice::whereIn('ad_customer_has_business_id', $assignedCustomerIds)
            ->where('created_at', '<=', $endDate)
            ->get();
            
        $currentDues = 0;
        $aged30d = 0;
        $aged60d = 0;
        $aged60dPlus = 0;
        $closingDues = 0;
        
        foreach($allInvoices as $inv) {
            $outstanding = max(0, $inv->net_price - $inv->total_amount_paid);
            if ($outstanding > 0) {
                $closingDues += $outstanding;
                
                $daysOld = $endDate->diffInDays(Carbon::parse($inv->created_at));
                
                if ($daysOld <= 30) {
                    $currentDues += $outstanding;
                } elseif ($daysOld <= 60) {
                    $aged30d += $outstanding;
                } elseif ($daysOld <= 90) {
                    $aged60d += $outstanding;
                } else {
                    $aged60dPlus += $outstanding;
                }
            }
        }
        
        $totalCreditLimit = $assignedCustomers->sum('credit_limit');
        $creditUtilization = $totalCreditLimit > 0 ? round(($closingDues / $totalCreditLimit) * 100) : 0;

        return [
            'success' => true,
            'agent' => [
                'name' => $agent->agent_name ?? 'Unknown',
                'code' => $agent->agent_code ?? 'N/A',
                'routes' => $routesString,
            ],
            'monthly_target' => $monthlyTargetVal,
            'sales' => [
                'current' => $monthlySales,
                'prev' => $prevMonthlySales,
                'growth' => $salesGrowth,
                'percent_target' => $targetProgressPercent,
                'remaining' => $remainingTarget
            ],
            'mom' => [
                'prev_month_label' => $prevStartDate->format('F'),
                'curr_month_label' => $startDate->format('F'),
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
            'week_by_week' => $weekByWeek,
            'policy_returns' => $policyReturns,
            'commission' => [
                'invoiced_value' => $monthlySales,
                'returns' => $totalReturns,
                'net_sales' => $netSales,
                'invoicing_rate' => $invoicingCommissionRate,
                'invoicing_commission' => $invoicingCommissionAmount,
                'target_rate' => $targetCommissionRate,
                'target_bonus' => $targetBonusAmount,
                'total_payable' => $totalPayable
            ],
            'credit' => [
                'closing_dues' => $closingDues,
                'utilization' => $creditUtilization,
                'current' => $currentDues,
                'days_1_30' => $aged30d, 
                'days_31_60' => $aged60d,
                'days_60_plus' => $aged60dPlus
            ]
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
            'companyInfo' => $companyInfo
        ])->setPaper('a4', 'portrait');

        $fileName = 'Monthly_Agent_Review_' . $data['agent']['name'] . '_' . $request->input('month') . '.pdf';
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
        
        $allAssignedCustomerIds = [];

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
                    'target' => $nextTargetVal
                ];
            } else {
                $nextMonthTargets[] = [
                    'agent_name' => $agent->agent_name,
                    'badge' => $badge,
                    'target' => 'Pending'
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
            foreach($allInvoices as $inv) {
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
                'target_rate' => $targetCommissionRate
            ];
        }

        // Sort Leaderboard by Achievement % DESC
        usort($leaderboard, function($a, $b) {
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
                'total_val' => $defectReturns + $otherReturns
            ];
        }

        // Credit Ageing Summary (All Agents)
        $allInvoicesOverall = AdCubusinessHasInvoice::where('created_at', '<=', $endDate)->get();
        $cCurrent = 0; $c30 = 0; $c60 = 0; $c60Plus = 0; $cTotal = 0;
        
        foreach($allInvoicesOverall as $inv) {
            $outstanding = max(0, $inv->net_price - $inv->total_amount_paid);
            if ($outstanding > 0) {
                $cTotal += $outstanding;
                $daysOld = $endDate->diffInDays(Carbon::parse($inv->created_at));
                
                if ($daysOld <= 30) {
                    $cCurrent += $outstanding;
                } elseif ($daysOld <= 60) {
                    $c30 += $outstanding;
                } elseif ($daysOld <= 90) {
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

        return [
            'leaderboard' => $leaderboard,
            'outlet_movement' => [
                'opening' => $totalActiveOpening,
                'new_shops' => $totalNewShops,
                'newly_dormant' => $totalDormant,
                'closing' => $closingActive,
                'growth_pct' => $activeGrowth
            ],
            'return_trend' => $months,
            'credit_ageing' => $creditAgeing,
            'next_targets' => [
                'label' => $nextMonthDate->format('M'),
                'targets' => $nextMonthTargets
            ]
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
            'companyInfo' => $companyInfo
        ])->setPaper('a4', 'landscape');

        $fileName = 'All_Agent_Performance_' . $request->input('month') . '.pdf';
        return $pdf->download($fileName);
    }
}
