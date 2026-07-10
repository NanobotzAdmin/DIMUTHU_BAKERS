<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdAgent;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdCubusinessHasInvoice;
use App\Models\AdInvoice;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
}
