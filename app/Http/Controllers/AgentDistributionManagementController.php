<?php

namespace App\Http\Controllers;

use App\CommonVariables;
use App\Models\AdAgent;
use App\Models\AdAgentHasBankAccount;
use App\Models\AdAgentHasCategoryTargets;
use App\Models\AdAgentHasItemTargets;
use App\Models\AdAgentMonthlyTarget;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdDailyLoad;
use App\Models\AdDailyLoadItem;
use App\Models\AdRoute;
use App\Models\CmCustomer;
use App\Models\PmProductCategory;
use App\Models\PmProductItem;
use App\Models\StmOrderRequest;
use App\Models\UmUser;
use App\Models\AdSettlement;
use App\Models\SoBank;
use App\Models\DmDriver;
use App\Models\SmSuperviser;
use App\Models\VmVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgentDistributionManagementController extends Controller
{
    public function agentDistributionSystemIndex()
    {
        $stats = [
            'activeAgents' => 12,
            'totalRoutes' => 8,
            'activeLoads' => 5,
            'pendingSettlements' => 3,
        ];

        return view('agentDistribution.dashboard', compact('stats'));
    }

    public function agentManageIndex()
    {
        // Fetch agents from database with bank accounts
        $agents = AdAgent::with(['primaryBankAccount', 'bankAccounts'])->get()->map(function ($agent) {
            return [
                'id' => $agent->id,
                'agentName' => $agent->agent_name,
                'agentCode' => $agent->agent_code,
                'agentType' => $agent->agent_type,
                'employmentStatus' => $agent->status,
                'contactPhone' => $agent->phone,
                'contactEmail' => $agent->email,
                'nicNumber' => $agent->nic_number,
                'address' => $agent->address,
                'baseSalary' => $agent->base_salary,
                'commissionRate' => $agent->commission_rate,
                'creditLimit' => $agent->credit_limit,
                'creditPeriodDays' => $agent->credit_period_days,
                'vehicleCategory' => $agent->vehicle_category,
                'bank_accounts' => $agent->bankAccounts->map(
                    function ($ba) {
                        return [
                            'bank_id' => $ba->bank_id,
                            'bank_name' => $ba->bank_name,
                            'account_owner_name' => $ba->account_owner_name,
                            'account_number' => $ba->account_number,
                            'branch' => $ba->branch,
                            'is_primary' => $ba->is_primary,
                        ];
                    }
                )->toArray(),
                'outstandingBalance' => $agent->outstanding_balance,
            ];
        });

        // Fetch product items and categories for target dropdowns
        $productItems = PmProductItem::where('status', 1)->get(['id', 'product_name']);
        $productCategories = PmProductCategory::where('is_active', true)->get(['id', 'category_name', 'category_code']);
        
        // Fetch active banks for dropdown
        $soBanks = SoBank::where('is_active', 1)->get(['id', 'bank_name', 'bank_code']);

        return view('agentDistribution.agentManagement', compact('agents', 'productItems', 'productCategories', 'soBanks'));
    }

    public function dailyLoadsIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$Active)->get();
        $routes = AdRoute::where('status', CommonVariables::$Active)->get();

        // Fetch Loads with relationships
        $loadsData = AdDailyLoad::with(['agent', 'route', 'items.product'])
            ->orderBy('load_date', 'desc')
            ->get();

        // Transform for view
        $loads = $loadsData->map(function ($load) {
            $totalQty = $load->items->sum('quantity');
            $totalVal = $load->items->sum('total_value');

            return [
                'id' => $load->id,
                'loadNumber' => 'LOAD-' . $load->id, // Simple ID based number for now
                'agentId' => $load->agent_id,
                'status' => $this->getLoadStatusLabel($load->status),
                'status_code' => $load->status,
                'loadDate' => $load->load_date->format('Y-m-d'),
                'totalQuantity' => $totalQty,
                'totalValue' => $totalVal,
                'items' => $load->items->map(
                    function ($item) {
                        return [
                            'id' => $item->id,
                            'productName' => $item->product ? $item->product->product_name : 'Unknown',
                            'loadedQuantity' => $item->quantity,
                            'unitPrice' => $item->price,
                        ];
                    }
                )->toArray(),
                'notes' => $load->notes,
                'isMarkedAsLoaded' => $load->is_mark_as_loaded,
            ];
        })->toArray();

        return view('agentDistribution.dailyLoads', compact('agents', 'routes', 'loads'));
    }

    private function getLoadStatusLabel($status)
    {
        switch ($status) {
            case CommonVariables::$dailyLoadStatusDraft:
                return 'draft';
            case CommonVariables::$dailyLoadStatusLoaded:
                return 'loaded';
            case CommonVariables::$dailyLoadStatusCompleted:
                return 'completed';
            default:
                return 'draft';
        }
    }

    public function distributorCustomerManageIndex()
    {
        // Get active customers (B2B and B2C) for builder
        $customers = CmCustomer::with(['businessDetails'])
            ->whereIn('customer_type', [CommonVariables::$customerTypeB2B, CommonVariables::$customerTypeB2C])
            ->get()
            ->map(function ($customer) {
                // Determine contact details based on customer type/data availability
                $details = $customer->businessDetails;
                $contactName = $details->contact_person_name ?? $customer->name; // Fallback? Or strict? Plan said new table has contact.
                $contactPhone = $details->contact_person_phone ?? $customer->phone;

                return [
                    'id' => $customer->id,
                    'customerType' => $customer->customer_type == CommonVariables::$customerTypeB2B ? 'b2b' : 'b2c',
                    'businessName' => $customer->name,
                    'tradeName' => $customer->name, // Simplification for now
                    'b2bType' => $this->getB2BTypeName($details->b2b_customer_type ?? 0),
                    'contact' => [
                        'contactPerson' => $details->contact_person_name ?? '',
                        'phoneNumber' => $details->contact_person_phone ?? $customer->phone,
                        'email' => $details->contact_person_email ?? $customer->email,
                    ],
                    'location' => [
                        'address' => $customer->address,
                        'city' => 'Unknown',
                        'district' => 'Unknown',
                        'latitude' => 0,
                        'longitude' => 0,
                    ],
                    'assignedAgentId' => $details->agent_id ?? '',
                    'assignedRouteId' => $details->route_id ?? '',
                    'stopSequence' => $details->stop_sequence ?? '',
                    'status' => 'active',
                    'currentBalance' => 0,
                    'totalSales' => 0, // Fix NaN
                    'totalOrders' => 0,
                    'creditDays' => 0,
                    'visitSchedule' => [
                        'frequency' => $this->getVisitScheduleName($details->visit_schedule ?? 1),
                        'preferredDays' => $details->preferred_visit_days ?? [],
                        'preferredTime' => $details->preferred_time ?? '',
                    ],
                    'creditTerms' => [
                        'allowCredit' => (bool) ($details->allow_credit ?? false),
                        'creditLimit' => $details->credit_limit ?? 0,
                        'paymentTermsDays' => $details->payment_terms_days ?? 0, // Should this be mapped to drop down values?
                        // frontend js sets document.getElementById('cust-terms').value.
                        // Dropdown values: 0, 7, 15, 30.
                    ],
                    'specialInstructions' => $details->special_instructions ?? '',
                    'deliveryInstructions' => $details->delivery_instructions ?? '',
                    'notes' => $details->notes ?? '',
                    // Pass raw data for edit modal too
                    'b2b_customer_type' => $details->b2b_customer_type ?? null,
                    'payment_terms' => $details->payment_terms ?? 1,
                    // ... other fields needed for edit
                ];
            });

        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($a) {
                return ['id' => $a->id, 'agentName' => $a->agent_name, 'agentCode' => $a->agent_code];
            });

        $routes = AdRoute::where('status', 1)->get()
            ->map(function ($r) {
                return ['id' => $r->id, 'routeName' => $r->route_name];
            });

        $b2bTypes = [
            ['id' => 'wholesale', 'label' => 'Wholesale'],
            ['id' => 'retail_shop', 'label' => 'Retail Shop'],
            ['id' => 'restaurant', 'label' => 'Restaurant'],
            ['id' => 'hotel', 'label' => 'Hotel'],
            ['id' => 'agent', 'label' => 'Agent'],
            ['id' => 'other', 'label' => 'Other'],
        ];

        return view('agentDistribution.customerManagement', compact('customers', 'agents', 'routes', 'b2bTypes'));
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

    public function settlementListIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($a) {
                return ['id' => $a->id, 'agentName' => $a->agent_name, 'agentCode' => $a->agent_code];
            });

        $settlements = AdSettlement::with(['agent'])
            ->orderBy('settlement_date', 'desc')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'agentId' => $s->agent_id,
                    'settlementNumber' => $s->settlement_number,
                    'settlementDate' => $s->settlement_date,
                    'status' => $s->status,
                    'totalSales' => $s->total_sales,
                    'expectedCash' => $s->cash_sales, // In the mock, actualCash is what agent submitted. Here cash_sales is what they submitted. 
                    'actualCash' => $s->cash_sales,   // Assuming cash_sales is the physical cash they claim to have.
                    'cashVariance' => 0, // We might need to calculate this against invoices later
                    'totalCollections' => 0, // Placeholder
                    'returnedValue' => 0,    // Placeholder
                    'amountDueToBakery' => $s->cash_sales, // Simplified
                    'commissionEarned' => $s->commission_earned,
                    'varianceNotes' => '',
                    'notes' => '',
                    'submittedAt' => $s->created_at->tz('Asia/Colombo')->format('Y-m-d H:i:s'),
                ];
            });

        return view('agentDistribution.settlementList', compact('agents', 'settlements'));
    }

    public function settlementDetail($id)
    {
        $settlementData = AdSettlement::with(['agent', 'route', 'dailyLoad'])->find($id);

        if (!$settlementData) {
            return redirect()->route('settlementList.index')->with('error', 'Settlement not found');
        }

        $settlement = [
            'id' => $settlementData->id,
            'settlementNumber' => $settlementData->settlement_number,
            'agentId' => $settlementData->agent_id,
            'settlementDate' => $settlementData->settlement_date,
            'status' => $settlementData->status,
            'totalSales' => $settlementData->total_sales,
            'cashSales' => $settlementData->cash_sales,
            'creditSales' => $settlementData->credit_sales,
            'chequeSales' => $settlementData->cheque_sales,
            'expectedCash' => $settlementData->cash_sales, // Simplified
            'actualCash' => $settlementData->cash_sales,
            'cashVariance' => 0,
            'totalCollections' => 0,
            'returnedValue' => 0,
            'amountDueToBakery' => $settlementData->cash_sales,
            'commissionEarned' => $settlementData->commission_earned,
            'loadedValue' => 0,
            'submittedAt' => $settlementData->created_at->tz('Asia/Colombo')->format('Y-m-d H:i:s'),
            'varianceNotes' => '',
            'notes' => $settlementData->notes,
        ];

        $agent = [
            'id' => $settlementData->agent->id,
            'agentName' => $settlementData->agent->agent_name,
            'agentCode' => $settlementData->agent->agent_code,
            'agentType' => $settlementData->agent->agent_type,
            'commissionRate' => $settlementData->agent->commission_rate
        ];
        $load = null;
        if ($settlementData->dailyLoad) {
            $load = [
                'loadNumber' => 'LOAD-' . $settlementData->dailyLoad->id,
                'loadDate' => $settlementData->dailyLoad->load_date->format('Y-m-d'),
                'totalQuantity' => 0,
                'totalValue' => 0
            ];
        }

        $sales = [];

        $collections = [];

        $returns = [];

        return view('agentDistribution.settlementDetail', compact('settlement', 'agent', 'load', 'sales', 'collections', 'returns'));
    }

    public function getAgentDailyLoads($agentId)
    {
        $loads = AdDailyLoad::where('agent_id', $agentId)
            ->where('load_status', '>=', 1) // Assuming 1+ means it's a valid load
            ->orderBy('load_date', 'desc')
            ->get()
            ->map(function ($l) {
                return [
                    'id' => $l->id,
                    'load_number' => $l->load_number,
                    'load_date' => $l->load_date
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $loads
        ]);
    }

    public function storeSettlement(Request $request)
    {
        $request->validate([
            'agent_id' => 'required',
            'daily_load_id' => 'required',
            'settlement_date' => 'required|date',
            'total_sales' => 'required|numeric',
            'cash_sales' => 'required|numeric',
            'credit_sales' => 'required|numeric',
            'cheque_sales' => 'required|numeric',
            'commission_earned' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        try {
            // Generate a settlement number (e.g., SET-YYYYMMDD-ID)
            $datePart = date('Ymd', strtotime($request->settlement_date));
            $lastSettlement = AdSettlement::where('settlement_number', 'LIKE', "SET-$datePart-%")
                ->orderBy('id', 'desc')
                ->first();

            $sequence = 1;
            if ($lastSettlement) {
                $lastNum = explode('-', $lastSettlement->settlement_number);
                $sequence = (int) end($lastNum) + 1;
            }
            $settlementNumber = "SET-$datePart-" . str_pad($sequence, 3, '0', STR_PAD_LEFT);

            $settlement = AdSettlement::create([
                'agent_id' => $request->agent_id,
                'daily_load_id' => $request->daily_load_id,
                'settlement_number' => $settlementNumber,
                'settlement_date' => $request->settlement_date,
                'total_sales' => $request->total_sales,
                'cash_sales' => $request->cash_sales,
                'credit_sales' => $request->credit_sales,
                'cheque_sales' => $request->cheque_sales,
                'commission_earned' => $request->commission_earned,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Settlement created successfully',
                'data' => $settlement
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create settlement: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSettlementStatus(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required|in:pending,reviewed,approved,disputed',
            'notes' => 'nullable|string'
        ]);

        try {
            $settlement = AdSettlement::findOrFail($request->id);
            $settlement->status = $request->status;
            if ($request->notes) {
                $settlement->notes = $request->notes;
            }
            $settlement->save();

            return response()->json([
                'status' => true,
                'message' => 'Settlement status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update settlement status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function glPostingIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'commissionRate' => 5.0],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'commissionRate' => 4.5],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'commissionRate' => 5.0],
        ];

        // Mock Settlements - Mix of Approved (Ready for Post) and Posted
        $settlements = [];
        $baseDate = date('Y-m-d');

        for ($i = 0; $i < 20; $i++) {
            $agent = $agents[rand(0, 2)];
            $isPosted = rand(0, 100) > 60; // 40% chance of being GL posted
            $date = date('Y-m-d', strtotime("$baseDate -$i days"));
            $sales = rand(15000, 40000);
            $commission = $sales * ($agent['commissionRate'] / 100);

            // Variance logic (some have variance)
            $expected = $sales * 0.6;
            $actual = $expected + (rand(0, 100) > 80 ? rand(-500, 200) : 0);
            $variance = $actual - $expected;

            $settlements[] = [
                'id' => "stl_gl_$i",
                'agentId' => $agent['id'],
                'settlementNumber' => 'SET-' . date('Ymd', strtotime($date)) . "-$i",
                'settlementDate' => $date,
                'status' => $isPosted ? 'gl_posted' : 'approved',
                'glPosted' => $isPosted,
                'glJournalEntryId' => $isPosted ? 'JE-AGT-' . strtotime($date) . "-$i" : null,
                'totalSales' => $sales,
                'actualCash' => $actual,
                'amountDueToBakery' => $actual, // Simplified
                'commissionEarned' => $commission,
                'cashVariance' => $variance,
                'varianceNotes' => $variance != 0 ? 'Cash mismatch' : '',
                'notes' => $isPosted ? 'Imported to Sage' : 'Approved by Manager',
            ];
        }

        return view('agentDistribution.glPosting', compact('agents', 'settlements'));
    }

    public function commissionOverviewIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)->get();

        $startDate = date('Y-m-d', strtotime('-30 days'));

        $stats = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->select(
                DB::raw('SUM(i.net_price) as total_sales'),
                DB::raw('SUM(i.return_price) as total_returns'),
                DB::raw('COUNT(i.id) as total_invoices')
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->first();

        $agentBreakdown = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->join('ad_agent as a', 'l.agent_id', '=', 'a.id')
            ->select(
                'a.agent_name',
                'a.agent_code',
                DB::raw('SUM(i.net_price) as sales'),
                DB::raw('SUM(i.net_price * 0.15) as commission')
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->groupBy('a.id', 'a.agent_name', 'a.agent_code')
            ->get();

        return view('agentDistribution.commissionOverview', compact('agents', 'stats', 'agentBreakdown'));
    }

    public function commissionPaymentIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                    'commissionRate' => $agent->commission_rate,
                    'invoicingCommissionRate' => $agent->invoicing_commission_rate,
                    'targetCommissionRate' => $agent->target_commission_rate,
                    'achievementThreshold' => $agent->achievement_threshold,
                    'reducedTargetCommissionRate' => $agent->reduced_target_commission_rate,
                    'monthlySalesTarget' => $agent->monthly_sales_target,
                    'status' => 1,
                ];
            });

        // Fetch Data from Invoices for the last 90 days
        $startDate = date('Y-m-d', strtotime('-90 days'));
        $settlements = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->select(
                'l.agent_id',
                'i.invoice_number as settlement_number',
                'i.net_price as total_sales',
                'i.invoice_price',
                'i.return_price',
                'i.created_at as settlement_date',
                'l.route_id',
                'i.status'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->agent_id . '_' . strtotime($s->settlement_date) . '_' . $s->settlement_number, // Generate a pseudo ID
                    'agentId' => $s->agent_id,
                    'settlementNumber' => $s->settlement_number,
                    'settlementDate' => $s->settlement_date,
                    'totalSales' => $s->total_sales,
                    'cashSales' => 0,
                    'creditSales' => 0,
                    'chequeSales' => 0,
                    'status' => $s->status == 1 ? 'approved' : 'pending', // Map to JS expectation
                    'commissionEarned' => 0, // Calculated in UI
                ];
            });

        return view('agentDistribution.commissionPayments', compact('agents', 'settlements'));
    }

    public function commissionStatementsIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                    'commissionRate' => $agent->commission_rate,
                    'invoicingCommissionRate' => $agent->invoicing_commission_rate,
                    'targetCommissionRate' => $agent->target_commission_rate,
                    'achievementThreshold' => $agent->achievement_threshold,
                    'reducedTargetCommissionRate' => $agent->reduced_target_commission_rate,
                    'monthlySalesTarget' => $agent->monthly_sales_target,
                    'status' => 1,
                ];
            });

        // Fetch Data from Invoices for the last 6 months
        $startDate = date('Y-m-d', strtotime('-180 days'));
        $settlements = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->select(
                'l.agent_id',
                'i.invoice_number as settlement_number',
                'i.net_price as total_sales',
                'i.invoice_price',
                'i.return_price',
                'i.created_at as settlement_date',
                'l.route_id',
                'i.status'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->agent_id . '_' . strtotime($s->settlement_date) . '_' . $s->settlement_number,
                    'agentId' => $s->agent_id,
                    'settlementNumber' => $s->settlement_number,
                    'settlementDate' => $s->settlement_date,
                    'totalSales' => $s->total_sales,
                    'cashSales' => 0,
                    'creditSales' => 0,
                    'chequeSales' => 0,
                    'status' => $s->status == 1 ? 'approved' : 'pending',
                    'commissionEarned' => 0,
                ];
            });

        return view('agentDistribution.commissionStatements', compact('agents', 'settlements'));
    }

    public function agentAnalyticsIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => (string) $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                ];
            });

        // Fetch Settlements for the last 90 days
        $startDate = date('Y-m-d', strtotime('-90 days'));

        $settlements = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->select(
                'l.agent_id',
                'i.net_price as total_sales',
                'i.created_at as settlement_date',
                'i.id as invoice_id'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => (string) $s->invoice_id,
                    'agentId' => (string) $s->agent_id,
                    'settlementDate' => date('Y-m-d', strtotime($s->settlement_date)),
                    'totalSales' => (double) $s->total_sales,
                    'cashVariance' => 0, // Placeholder as not directly in invoice table
                    'commissionEarned' => (double) $s->total_sales * 0.15, // Using standard 15% for overall analytics
                ];
            });

        // Fetch Individual Sales (Invoices with primary payment method)
        $sales = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->leftJoin('ad_cubusiness_invoice_payments as p', 'i.id', '=', 'p.ad_cubusiness_has_invoice_id')
            ->select(
                'l.agent_id',
                'i.created_at as sale_date',
                'i.net_price as total_amount',
                'p.payment_type'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                $method = 'cash';
                if ($s->payment_type) {
                    $pt = strtolower($s->payment_type);
                    if (str_contains($pt, 'cash'))
                        $method = 'cash';
                    elseif (str_contains($pt, 'credit'))
                        $method = 'credit';
                    elseif (str_contains($pt, 'cheque'))
                        $method = 'cheque';
                }
                return [
                    'agentId' => (string) $s->agent_id,
                    'saleDate' => date('Y-m-d', strtotime($s->sale_date)),
                    'paymentMethod' => $method,
                    'totalAmount' => (double) $s->total_amount,
                ];
            });

        return view('agentDistribution.agentAnalytics', compact('agents', 'settlements', 'sales'));
    }

    public function financialDashboardIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => (string) $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                    'commissionRate' => (double) $agent->commission_rate,
                ];
            });

        $startDate = date('Y-m-d', strtotime('-180 days'));

        $settlements = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->select(
                'l.agent_id',
                'i.invoice_number as settlement_number',
                'i.net_price as total_sales',
                'i.created_at as settlement_date',
                'i.status'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                return [
                    'agentId' => (string) $s->agent_id,
                    'settlementNumber' => $s->settlement_number,
                    'settlementDate' => date('Y-m-d', strtotime($s->settlement_date)),
                    'totalSales' => (double) $s->total_sales,
                    'cashSales' => (double) $s->total_sales, // Placeholder
                    'creditSales' => 0,
                    'chequeSales' => 0,
                    'actualCash' => (double) $s->total_sales,
                    'cashVariance' => 0,
                    'commissionEarned' => (double) $s->total_sales * 0.15,
                    'status' => 'approved',
                ];
            });

        $commissionPayments = [];

        return view('agentDistribution.financialDashboard', compact('agents', 'settlements', 'commissionPayments'));
    }

    public function performanceOverviewIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get(['id', 'agent_name', 'agent_code']);

        return view('agentDistribution.performanceOverview', compact('agents'));
    }

    public function getPerformanceOverviewData(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:ad_agent,id',
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
        ]);

        try {
            $agentId = $request->agent_id;
            $year = $request->year;
            $month = $request->month;

            $target = AdAgentMonthlyTarget::with(['categoryTargets.category'])
                ->where('agent_id', $agentId)
                ->where('target_year', $year)
                ->where('target_month', $month)
                ->first();

            // 1. Calculate Actual Sales (Agent to Customer Invoices)
            $salesData = DB::table('ad_cubusiness_has_invoice as i')
                ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
                ->where('l.agent_id', $agentId)
                ->where('i.status', 1) // Active/Approved Invoices
                ->whereMonth('i.created_at', $month)
                ->whereYear('i.created_at', $year)
                ->select(
                    DB::raw('SUM(i.net_price) as total_sales'),
                    DB::raw('COUNT(i.id) as invoice_count')
                )
                ->first();

            $totalSales = (double)($salesData->total_sales ?? 0);
            $invoiceCount = (int)($salesData->invoice_count ?? 0);

            // 2. Calculate "Getting Amount" (Stock requests from bakery)
            // Including all valid stages of getting stock: Approved to Settled
            $gettingStatuses = [
                CommonVariables::$orderRequestApproved,
                CommonVariables::$orderRequestProductionStarted,
                CommonVariables::$orderRequestReadyToDispatch,
                CommonVariables::$orderRequestDispatchCompleted,
                CommonVariables::$orderRequestDispatchConfirmed,
                CommonVariables::$orderRequestCompleteSettled
            ];

            $gettingAmount = StmOrderRequest::where('agent_id', $agentId)
                ->whereIn('status', $gettingStatuses)
                ->whereMonth('delivery_date', $month)
                ->whereYear('delivery_date', $year)
                ->sum('grand_total');

            // Calculate Temporary Commission based on REFINED Total Sales
            $tempCommission = 0;
            $commBreakdown = [
                'base_commission' => 0,
                'bonus_commission' => 0,
                'invoicing_rate' => 0,
                'bonus_rate' => 0,
                'is_target_achieved' => false,
                'achievement_pct' => 0,
                'achievement_msg' => 'No Target Set'
            ];

            if ($target) {
                $invoicingRate = (float)$target->invoicing_commission_rate;
                $bonusRate = 0;
                
                $achievementPct = $target->monthly_sales_target > 0 
                    ? ($totalSales / $target->monthly_sales_target) * 100 
                    : ($totalSales > 0 ? 100 : 0);
                
                $threshold = (float)($target->achievement_threshold ?? 80);
                $baseComm = 0;
                $bonusComm = 0;

                if ($achievementPct >= 100) {
                    $baseComm = ($totalSales * ($invoicingRate / 100));
                    $bonusRate = (float)$target->target_commission_rate;
                    $bonusComm = ($totalSales * ($bonusRate / 100));
                    $isTargetAchieved = true;
                    $achievementMsg = '100% Achieved (Full Bonus)';
                } elseif ($achievementPct >= $threshold) {
                    $bonusRate = (float)$target->reduced_target_commission_rate;
                    $bonusComm = ($totalSales * ($bonusRate / 100));
                    $isTargetAchieved = true;
                    $achievementMsg = number_format($achievementPct, 1) . '% Achieved (Reduced Bonus)';
                } else {
                    $bonusRate = (float)$target->reduced_target_commission_rate; // Still show the potential rate
                    $isTargetAchieved = false;
                    $achievementMsg = number_format($achievementPct, 1) . '% Achieved (No Bonus Yet)';
                }

                $commBreakdown = [
                    'base_commission' => $baseComm,
                    'bonus_commission' => $bonusComm,
                    'invoicing_rate' => $invoicingRate,
                    'bonus_rate' => $bonusRate,
                    'is_target_achieved' => $isTargetAchieved,
                    'achievement_pct' => $achievementPct,
                    'achievement_msg' => $achievementMsg
                ];

                $tempCommission = $bonusComm;
            }

            // 3. Prepare Chart Data (Trends - 6 Months ending at selected month)
            // Note: For trend chart, we use the same refined sales logic
            $history = [];
            for ($i = 5; $i >= 0; $i--) {
                $time = mktime(0, 0, 0, $month - $i, 1, $year);
                $hYear = (int)date('Y', $time);
                $hMonth = (int)date('n', $time);

                $hTarget = AdAgentMonthlyTarget::where('agent_id', $agentId)
                    ->where('target_year', $hYear)
                    ->where('target_month', $hMonth)
                    ->first();
                
                if ($hTarget) {
                    $history[] = $hTarget;
                } else {
                    // Create a pseudo-target with 0s if it doesn't exist for the trend line
                    $history[] = (object)[
                        'target_year' => $hYear,
                        'target_month' => $hMonth,
                        'monthly_sales_target' => 0,
                        'monthly_commission' => 0,
                        'payment_status' => 0
                    ];
                }
            }

            $trendLabels = [];
            $trendSales = [];
            $trendGetting = [];
            $trendTargets = [];
            $commissionHistory = [];

            foreach ($history as $h) {
                $label = date('M Y', mktime(0, 0, 0, $h->target_month, 1, $h->target_year));
                $trendLabels[] = $label;
                
                $monthSales = DB::table('ad_cubusiness_has_invoice as i')
                    ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
                    ->where('l.agent_id', $agentId)
                    ->where('i.status', 1)
                    ->whereMonth('i.created_at', $h->target_month)
                    ->whereYear('i.created_at', $h->target_year)
                    ->sum('i.net_price');

                $monthGetting = StmOrderRequest::where('agent_id', $agentId)
                    ->whereIn('status', $gettingStatuses)
                    ->whereMonth('delivery_date', $h->target_month)
                    ->whereYear('delivery_date', $h->target_year)
                    ->sum('grand_total');

                $trendSales[] = (double)$monthSales;
                $trendGetting[] = (double)$monthGetting;
                $trendTargets[] = (double)$h->monthly_sales_target;
                
                $commissionHistory[] = [
                    'label' => $label,
                    'amount' => (double)$h->monthly_commission,
                    'status' => $h->payment_status
                ];
            }

            $categoryBreakdown = [];
            if ($target) {
                foreach ($target->categoryTargets as $ct) {
                    $catSales = DB::table('ad_cubusiness_has_invoice as i')
                        ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
                        ->join('ad_cubusiness_has_product_item as ii', 'i.id', '=', 'ii.ad_cubusiness_has_invoice_id')
                        ->join('pm_product_item as pi', 'ii.pm_product_item_id', '=', 'pi.id')
                        ->where('l.agent_id', $agentId)
                        ->where('i.status', 1)
                        ->whereMonth('i.created_at', $month)
                        ->whereYear('i.created_at', $year)
                        ->where('pi.pm_product_category_id', $ct->pm_product_category_id)
                        ->sum('ii.total_price');

                    $catGetting = DB::table('stm_order_requests as o')
                        ->join('stm_order_requests_has_product as op', 'o.id', '=', 'op.stm_order_request_id')
                        ->join('pm_product_item as pi', 'op.pm_product_item_id', '=', 'pi.id')
                        ->where('o.agent_id', $agentId)
                        ->whereIn('o.status', $gettingStatuses)
                        ->whereMonth('o.delivery_date', $month)
                        ->whereYear('o.delivery_date', $year)
                        ->where('pi.pm_product_category_id', $ct->pm_product_category_id)
                        ->sum('op.subtotal');

                    $categoryBreakdown[] = [
                        'name' => $ct->category->category_name ?? 'Other',
                        'target' => (double)$ct->target_amount,
                        'actual' => (double)$catSales,
                        'getting' => (double)$catGetting
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'metrics' => [
                    'totalSales' => (double)$totalSales,
                    'gettingAmount' => (double)$gettingAmount,
                    'targetAmount' => $target ? (double)$target->monthly_sales_target : 0,
                    'baseSalary' => $target ? (double)$target->base_salary : 0,
                    'commission' => (double)$tempCommission,
                    'tempCommission' => (double)$tempCommission,
                    'commissionBreakdown' => $commBreakdown,
                    'paymentStatus' => $target ? $target->payment_status : 0,
                    'orderCount' => $invoiceCount,
                    'achievement' => $target && $target->monthly_sales_target > 0 ? ($totalSales / $target->monthly_sales_target) * 100 : 0
                ],
                'charts' => [
                    'trend' => [
                        'labels' => $trendLabels,
                        'sales' => $trendSales,
                        'getting' => $trendGetting,
                        'targets' => $trendTargets
                    ],
                    'categories' => $categoryBreakdown,
                    'commissions' => $commissionHistory
                ],
                'target_details' => $target
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function agentDistributionReportIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => (string) $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                    'commissionRate' => (double) $agent->commission_rate,
                ];
            });

        $startDate = date('Y-m-d', strtotime('-180 days'));

        $settlements = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->select(
                'l.agent_id',
                'i.invoice_number as settlement_number',
                'i.net_price as total_sales',
                'i.return_price',
                'i.created_at as settlement_date',
                'i.status'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                return [
                    'agentId' => (string) $s->agent_id,
                    'settlementNumber' => $s->settlement_number,
                    'settlementDate' => date('Y-m-d', strtotime($s->settlement_date)),
                    'totalSales' => (double) $s->total_sales,
                    'cashSales' => (double) $s->total_sales, // Simple assumption for report placeholder
                    'creditSales' => 0,
                    'chequeSales' => 0,
                    'actualCash' => (double) $s->total_sales,
                    'cashVariance' => 0,
                    'commissionEarned' => (double) $s->total_sales * 0.15,
                    'status' => 'approved',
                    'varianceNotes' => '',
                ];
            });

        // Collections from payments table
        $sales = DB::table('ad_cubusiness_has_invoice as i')
            ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
            ->leftJoin('ad_cubusiness_invoice_payments as p', 'i.id', '=', 'p.ad_cubusiness_has_invoice_id')
            ->select(
                'l.agent_id',
                'i.created_at as sale_date',
                'p.payment_type',
                'p.amount as total_amount'
            )
            ->where('i.created_at', '>=', $startDate)
            ->where('i.status', 1)
            ->get()
            ->map(function ($s) {
                $method = 'cash';
                if ($s->payment_type) {
                    $pt = strtolower($s->payment_type);
                    if (str_contains($pt, 'cash'))
                        $method = 'cash';
                    elseif (str_contains($pt, 'credit'))
                        $method = 'credit';
                    elseif (str_contains($pt, 'cheque'))
                        $method = 'cheque';
                }
                return [
                    'agentId' => (string) $s->agent_id,
                    'saleDate' => date('Y-m-d', strtotime($s->sale_date)),
                    'paymentMethod' => $method,
                    'totalAmount' => (double) ($s->total_amount ?? 0),
                ];
            });

        return view('agentDistribution.reports', compact('agents', 'settlements', 'sales'));
    }

    public function sattlementAutomationIndex()
    {
        // Mock settlements for automation testing
        $settlements = [
            ['id' => 'stl_101', 'settlementNumber' => 'SET-2026-101', 'settlementDate' => '2026-01-10', 'totalSales' => 15000, 'cashVariance' => 0, 'status' => 'pending', 'glPosted' => false],
            ['id' => 'stl_102', 'settlementNumber' => 'SET-2026-102', 'settlementDate' => '2026-01-11', 'totalSales' => 22000, 'cashVariance' => -50, 'status' => 'pending', 'glPosted' => false],
            ['id' => 'stl_103', 'settlementNumber' => 'SET-2026-103', 'settlementDate' => '2026-01-12', 'totalSales' => 18000, 'cashVariance' => 0, 'status' => 'reviewed', 'glPosted' => false],
            ['id' => 'stl_104', 'settlementNumber' => 'SET-2026-104', 'settlementDate' => '2026-01-12', 'totalSales' => 30000, 'cashVariance' => -1200, 'status' => 'pending', 'glPosted' => false], // High variance
            ['id' => 'stl_105', 'settlementNumber' => 'SET-2026-105', 'settlementDate' => '2026-01-13', 'totalSales' => 25000, 'cashVariance' => 0, 'status' => 'approved', 'glPosted' => false],
            ['id' => 'stl_106', 'settlementNumber' => 'SET-2026-106', 'settlementDate' => '2026-01-13', 'totalSales' => 12000, 'cashVariance' => -10, 'status' => 'pending', 'glPosted' => false],
        ];

        return view('agentDistribution.settlementAutomation', compact('settlements'));
    }

    public function incentivesAndBonusesIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'status' => 'active'],
        ];

        // Mock settlements for calculations (current month and previous)
        $settlements = [
            // Agent 1 - High Performer
            ['id' => 'stl_1', 'agentId' => 'agt_1', 'settlementDate' => '2026-01-05', 'totalSales' => 150000, 'cashVariance' => 0],
            ['id' => 'stl_2', 'agentId' => 'agt_1', 'settlementDate' => '2026-01-10', 'totalSales' => 120000, 'cashVariance' => 0],
            ['id' => 'stl_3', 'agentId' => 'agt_1', 'settlementDate' => '2026-01-15', 'totalSales' => 140000, 'cashVariance' => 0],

            // Agent 2 - Medium Performer
            ['id' => 'stl_4', 'agentId' => 'agt_2', 'settlementDate' => '2026-01-05', 'totalSales' => 80000, 'cashVariance' => 0],
            ['id' => 'stl_5', 'agentId' => 'agt_2', 'settlementDate' => '2026-01-12', 'totalSales' => 90000, 'cashVariance' => -100],

            // Agent 3 - Low Performer
            ['id' => 'stl_6', 'agentId' => 'agt_3', 'settlementDate' => '2026-01-08', 'totalSales' => 50000, 'cashVariance' => -500],
        ];

        return view('agentDistribution.incentivesAndBonuses', compact('agents', 'settlements'));
    }

    public function disputeResolutionIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'status' => 'active'],
        ];

        $settlements = [
            ['id' => 'stl_1', 'settlementNumber' => 'SET-2026-001', 'agentId' => 'agt_1', 'status' => 'disputed', 'cashVariance' => -500, 'notes' => ''],
            ['id' => 'stl_2', 'settlementNumber' => 'SET-2026-002', 'agentId' => 'agt_2', 'status' => 'reviewed', 'cashVariance' => 0, 'notes' => ''],
            ['id' => 'stl_3', 'settlementNumber' => 'SET-2026-003', 'agentId' => 'agt_3', 'status' => 'disputed', 'cashVariance' => -1200, 'notes' => ''],
        ];

        $disputes = [
            [
                'id' => 'dispute_1',
                'settlementId' => 'stl_1',
                'settlementNumber' => 'SET-2026-001',
                'agentId' => 'agt_1',
                'agentName' => 'John Doe',
                'type' => 'cash_variance',
                'status' => 'open',
                'priority' => 'high',
                'amount' => -500,
                'description' => 'Cash collected is less than expected total. Agent claims systemic error.',
                'agentNotes' => 'I counted exact cash as per receipt.',
                'createdAt' => '2026-01-10T09:00:00',
                'timeline' => [
                    ['id' => 'tl_1', 'timestamp' => '2026-01-10T09:00:00', 'user' => 'Manager', 'action' => 'Dispute created'],
                ],
            ],
            'tradeName' => 'Sunshine Sweets',
            'customerType' => 'b2b',
            'b2bType' => 'retail_shop',
            'status' => 'active',
            'isVerified' => true,
            'contact' => [
                'contactPerson' => 'Mr. Perera',
                'phoneNumber' => '+94 77 123 4567',
                'alternatePhone' => '+94 11 234 5678',
                'email' => 'sunshine@example.com',
            ],
            'location' => [
                'address' => '123 Galle Road',
                'city' => 'Colombo 03',
                'district' => 'Colombo',
                'latitude' => 6.9048,
                'longitude' => 79.8526,
            ],
            'assignedAgentId' => 'agt_1',
            'assignedRouteId' => 'rt_1',
            'stopSequence' => 5,
            'visitSchedule' => [
                'frequency' => 'weekly',
                'preferredDays' => ['monday', 'thursday'],
                'preferredTime' => 'morning',
                'visitCount' => 45,
                'lastVisitDate' => date('Y-m-d', strtotime('-3 days')),
                'nextScheduledVisit' => date('Y-m-d', strtotime('+1 day')),
            ],
            'specialInstructions' => 'Call before arrival on Mondays.',
            'deliveryInstructions' => 'Use the back gate for deliveries.',
            'notes' => 'Long-standing customer, reliable payments.',
            'creditTerms' => [
                'creditLimit' => 100000,
                'paymentTermsDays' => 30,
            ],
            'currentBalance' => 35000,
            'creditDays' => 25,
            'totalSales' => 1500000,
            'averageOrderValue' => 25000,
            'totalOrders' => 60,
            'lastOrderDate' => date('Y-m-d', strtotime('-3 days')),
            'lastPaymentDate' => date('Y-m-d', strtotime('-5 days')),
            'invoiceAging' => [
                'current' => 20000,
                'days30' => 10000,
                'days60' => 5000,
                'days90Plus' => 0,
            ],
            'createdAt' => '2025-01-15T10:00:00',
            'updatedAt' => date('Y-m-d H:i:s'),
        ];

        // Mock Related Entitics
        $agent = ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001'];
        $route = ['id' => 'rt_1', 'routeName' => 'Colombo North', 'routeCode' => 'R001'];

        // Mock Tabs Data
        // Mock Tabs Data
        $orders = [];
        for ($i = 0; $i < 10; $i++) {
            $itemCount = rand(1, 5);
            $items = [];
            $orderTotal = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $qty = rand(5, 50);
                $price = rand(100, 500);
                $total = $qty * $price;
                $orderTotal += $total;

                $items[] = [
                    'productName' => ['Bun', 'Bread', 'Cake', 'Pastry', 'Muffin'][rand(0, 4)] . ' ' . chr(65 + rand(0, 5)),
                    'quantity' => $qty,
                    'unitPrice' => $price,
                    'total' => $total,
                ];
            }

            $orders[] = [
                'id' => "ord_$i",
                'orderNumber' => 'ORD-' . date('Ymd') . "-$i",
                'date' => date('Y-m-d', strtotime("-$i weeks")),
                'deliveryDate' => date('Y-m-d', strtotime("-$i weeks + 1 day")),
                'totalAmount' => $orderTotal,
                'status' => ['pending', 'confirmed', 'delivered', 'cancelled'][rand(0, 3)],
                'paymentMethod' => ['cash', 'credit', 'cheque'][rand(0, 2)],
                'agentName' => 'John Doe',
                'items' => $items,
                'notes' => rand(0, 1) ? 'Urgent delivery requested' : null,
            ];
        }

        $payments = [];
        for ($i = 0; $i < 5; $i++) {
            $amount = rand(10000, 50000);
            $allocations = [];

            // Mock FIFO Allocations
            if (rand(0, 1)) {
                $allocations[] = [
                    'invoiceNumber' => 'INV-' . date('Ymd') . '-' . rand(100, 999),
                    'allocatedAmount' => $amount * 0.6,
                ];
                $allocations[] = [
                    'invoiceNumber' => 'INV-' . date('Ymd') . '-' . rand(100, 999),
                    'allocatedAmount' => $amount * 0.4,
                ];
            }

            $payments[] = [
                'id' => "pay_$i",
                'receiptNumber' => 'REC-' . date('Ymd') . "-$i",
                'date' => date('Y-m-d', strtotime("-$i months")),
                'amount' => $amount,
                'method' => ['cash', 'cheque', 'bank_transfer', 'card'][rand(0, 3)],
                'status' => 'verified',
                'agentName' => 'John Doe',
                'reference' => rand(0, 1) ? 'REF-' . rand(1000, 9999) : null,
                'allocations' => $allocations,
                'notes' => rand(0, 1) ? 'Payment received with thanks' : null,
            ];
        }

        $visits = [];
        for ($i = 0; $i < 10; $i++) {
            $visitDate = date('Y-m-d', strtotime("-$i days"));
            $checkIn = date('H:i', strtotime('09:00 + ' . ($i * 30) . ' minutes'));
            $checkOut = date('H:i', strtotime($checkIn . ' + ' . rand(10, 45) . ' minutes'));

            $status = ['completed', 'skipped', 'in_progress'][rand(0, 2)];
            $orderPlaced = rand(0, 1);
            $paymentCollected = rand(0, 1);

            $visits[] = [
                'id' => "vis_$i",
                'visitNumber' => 'VISIT-' . date('Ymd') . "-$i",
                'date' => $visitDate,
                'checkInTime' => $checkIn,
                'checkOutTime' => $status === 'in_progress' ? null : $checkOut,
                'duration' => $status === 'in_progress' ? null : rand(10, 45),
                'status' => $status,
                'agentName' => 'John Doe',
                'agentId' => 'agt_1',
                'type' => 'scheduled',
                'orderPlaced' => $orderPlaced,
                'orderValue' => $orderPlaced ? rand(5000, 20000) : 0,
                'paymentCollected' => $paymentCollected,
                'paymentAmount' => $paymentCollected ? rand(2000, 10000) : 0,
                'photosTaken' => rand(0, 3),
                'skipReason' => $status === 'skipped' ? 'Store closed' : null,
                'location' => [
                    'latitude' => 6.9271 + (rand(-100, 100) / 10000),
                    'longitude' => 79.8612 + (rand(-100, 100) / 10000),
                    'accuracy' => rand(5, 20),
                ],
                'notes' => rand(0, 1) ? 'Customer requested new product catalog' : null,
                'feedback' => rand(0, 1) && $status === 'completed' ? [
                    'rating' => rand(3, 5),
                    'satisfaction' => ['happy', 'neutral'][rand(0, 1)],
                    'comments' => 'Good service',
                ] : null,
                'outcome' => 'visited',
            ];
        }

        // Mock Lists for Modal
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe'],
            ['id' => 'agt_2', 'agentName' => 'Jane Smith'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson'],
        ];

        $routes = [
            ['id' => 'rt_1', 'routeName' => 'Colombo North'],
            ['id' => 'rt_2', 'routeName' => 'Colombo South'],
            ['id' => 'rt_3', 'routeName' => 'Kandy Line'],
        ];

        return view('agentDistribution.customerDetail', compact('customer', 'agent', 'route', 'orders', 'payments', 'visits', 'agents', 'routes'));
    }

    /**
     * Create new agent
     */
    public function createAgent(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'agent_name' => 'required|string|max:255',
                'agent_type' => 'required|integer|in:1,2,3',
                'status' => 'nullable|integer|in:1,2',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'nic_number' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'credit_limit' => 'nullable|numeric|min:0',
                'credit_period_days' => 'nullable|integer|min:0',
                'bank_accounts' => 'required|array|min:1',
                'bank_accounts.*.bank_id' => 'required|exists:so_banks,id',
                'bank_accounts.*.account_owner_name' => 'nullable|string|max:255',
                'bank_accounts.*.account_number' => 'required|string|max:50',
                'bank_accounts.*.branch' => 'nullable|string|max:255',
                'bank_accounts.*.is_primary' => 'nullable|boolean',
                'vehicle_category' => 'nullable|string|max:255',
            ]);

            // Create user account first
            $defaultPassword = 123456;
            $userName = strtolower(str_replace(' ', '', $validated['agent_name'])) . '_agent';

            // Check if username exists, append number if needed
            $baseUserName = $userName;
            $counter = 1;
            while (UmUser::where('user_name', $userName)->exists()) {
                $userName = $baseUserName . $counter;
                $counter++;
            }

            $user = UmUser::create([
                'first_name' => $validated['agent_name'],
                'last_name' => '',
                'user_name' => $userName,
                'user_password' => Hash::make($defaultPassword),
                'contact_no' => $validated['phone'],
                'user_role_id' => 8,
                'is_active' => 1,
            ]);

            // Create agent (agent_code will be auto-generated)
            $agent = AdAgent::create([
                'user_id' => $user->id,
                'agent_name' => $validated['agent_name'],
                'agent_type' => $validated['agent_type'],
                'status' => $validated['status'] ?? CommonVariables::$agentStatusActive,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'nic_number' => $validated['nic_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'credit_period_days' => $validated['credit_period_days'] ?? null,
                'vehicle_category' => $validated['vehicle_category'] ?? null,
            ]);

            // Create bank accounts
            if (isset($validated['bank_accounts']) && is_array($validated['bank_accounts'])) {
                foreach ($validated['bank_accounts'] as $bankAccount) {
                    $bank = SoBank::find($bankAccount['bank_id']);
                    AdAgentHasBankAccount::create([
                        'agent_id' => $agent->id,
                        'bank_id' => $bankAccount['bank_id'],
                        'bank_name' => $bank ? $bank->bank_name : 'Unknown',
                        'account_owner_name' => $bankAccount['account_owner_name'] ?? null,
                        'account_number' => $bankAccount['account_number'],
                        'branch' => $bankAccount['branch'] ?? null,
                        'is_primary' => $bankAccount['is_primary'] ?? false,
                    ]);
                }
            }

            // Bank accounts are created above

            return response()->json([
                'success' => true,
                'message' => 'Agent created successfully',
                'agent_code' => $agent->agent_code,
                'user_name' => $userName,
                'default_password' => $defaultPassword,
                'agent' => $agent,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating agent: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Load single agent details
     */
    public function loadAgentDetails($id)
    {
        try {
            $agent = AdAgent::with('bankAccounts')->findOrFail($id);

            return response()->json([
                'success' => true,
                'agent' => [
                    'id' => $agent->id,
                    'agent_code' => $agent->agent_code,
                    'agent_name' => $agent->agent_name,
                    'agent_type' => $agent->agent_type,
                    'status' => $agent->status,
                    'phone' => $agent->phone,
                    'email' => $agent->email,
                    'nic_number' => $agent->nic_number,
                    'address' => $agent->address,
                    'credit_limit' => $agent->credit_limit,
                    'credit_period_days' => $agent->credit_period_days,
                    'vehicle_category' => $agent->vehicle_category,
                    'bank_accounts' => $agent->bankAccounts->map(function ($bankAccount) {
                        return [
                            'bank_id' => $bankAccount->bank_id,
                            'bank_name' => $bankAccount->bank_name,
                            'account_owner_name' => $bankAccount->account_owner_name,
                            'account_number' => $bankAccount->account_number,
                            'branch' => $bankAccount->branch,
                            'is_primary' => $bankAccount->is_primary,
                        ];
                    }),
                    'outstanding_balance' => $agent->outstanding_balance,
                    'total_sales' => $agent->total_sales,
                    'total_collections' => $agent->total_collections,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading agent: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update agent
     */
    public function updateAgent(Request $request, $id)
    {
        try {
            $agent = AdAgent::findOrFail($id);

            // Validate request
            $validated = $request->validate([
                'agent_name' => 'required|string|max:255',
                'agent_type' => 'required|integer|in:1,2,3',
                'status' => 'nullable|integer|in:1,2',
                'phone' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'nic_number' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'credit_limit' => 'nullable|numeric|min:0',
                'credit_period_days' => 'nullable|integer|min:0',
                'bank_accounts' => 'required|array|min:1',
                'bank_accounts.*.bank_id' => 'required|exists:so_banks,id',
                'bank_accounts.*.account_owner_name' => 'nullable|string|max:255',
                'bank_accounts.*.account_number' => 'required|string|max:50',
                'bank_accounts.*.branch' => 'nullable|string|max:255',
                'bank_accounts.*.is_primary' => 'nullable|boolean',
                'vehicle_category' => 'nullable|string|max:255',
            ]);

            // Update agent
            $agent->update([
                'agent_name' => $validated['agent_name'],
                'agent_type' => $validated['agent_type'],
                'status' => $validated['status'] ?? $agent->status,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'nic_number' => $validated['nic_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'credit_period_days' => $validated['credit_period_days'] ?? null,
                'vehicle_category' => $validated['vehicle_category'] ?? null,
            ]);

            // Delete existing bank accounts and create new ones
            if (isset($validated['bank_accounts']) && is_array($validated['bank_accounts'])) {
                // Delete all existing bank accounts for this agent
                $agent->bankAccounts()->delete();

                // Create new bank accounts
                foreach ($validated['bank_accounts'] as $bankAccount) {
                    $bank = SoBank::find($bankAccount['bank_id']);
                    AdAgentHasBankAccount::create([
                        'agent_id' => $agent->id,
                        'bank_id' => $bankAccount['bank_id'],
                        'bank_name' => $bank ? $bank->bank_name : 'Unknown',
                        'account_owner_name' => $bankAccount['account_owner_name'] ?? null,
                        'account_number' => $bankAccount['account_number'],
                        'branch' => $bankAccount['branch'] ?? null,
                        'is_primary' => $bankAccount['is_primary'] ?? false,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Agent updated successfully',
                'agent' => $agent,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating agent: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle agent status (Active/Inactive)
     */
    public function toggleAgentStatus($id)
    {
        try {
            $agent = AdAgent::findOrFail($id);
            $newStatus = ($agent->status == CommonVariables::$agentStatusActive)
                ? CommonVariables::$agentStatusInactive
                : CommonVariables::$agentStatusActive;

            $agent->update([
                'status' => $newStatus,
            ]);

            $statusText = ($newStatus == CommonVariables::$agentStatusActive) ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => 'Agent ' . $statusText . ' successfully',
                'new_status' => $newStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error toggling status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deactivate agent
     */
    public function deactivateAgent($id)
    {
        try {
            $agent = AdAgent::findOrFail($id);

            $agent->update([
                'status' => CommonVariables::$agentStatusInactive,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Agent deactivated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deactivating agent: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Route Management - Display routes list
     */
    public function routeManageIndex()
    {
        $routes = AdRoute::with(['agent', 'customers.customer'])->get()->map(function ($route) {
            // Map customers assigned to this route
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

        // Get active agents for dropdown
        $agents = AdAgent::orderBy('agent_name')->where('status',CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                ];
            });

        // Get active customers (B2B and B2C) for builder
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
            ->get()
            ->map(function ($c) {
                $d = $c->businessDetails;

                // If no business details exist, create defaults so customer still appears
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
                        'latitude' => $c->latitude ?? 6.9271, // Real from database
                        'longitude' => $c->longitude ?? 79.8612, // Real from database
                    ],
                    'contact' => [
                        'contactPerson' => $d->contact_person_name ?? '',
                        'phoneNumber' => $c->phone ?? '',
                    ],
                    'assignedRouteId' => $d->route_id ?? null,
                    'stopSequence' => $d->stop_sequence ?? null,
                    'savedDistance' => $c->saved_distance, // From ad_route_has_customers
                    'savedDuration' => $c->saved_duration, // From ad_route_has_customers
                ];
            })
            ->filter() // Remove null entries
            ->values(); // Re-index array

        $googleMapsKey = config('services.google.maps_key');

        return view('agentDistribution.routeManagement', compact('routes', 'agents', 'customers', 'googleMapsKey'));
    }

    public function routeBuilderView($id)
    {
        // Get route details
        $route = AdRoute::with('agent')->findOrFail($id);

        // Get all agents for dropdown
        $agents = AdAgent::where('status', 1)->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'agentName' => $agent->agent_name,
                    'agentCode' => $agent->agent_code,
                ];
            });

        // Get active customers with saved distance/duration
        $customers = CmCustomer::with(['businessDetails'])
            ->leftJoin('ad_customer_has_business', 'cm_customer.id', '=', 'ad_customer_has_business.customer_id')
            ->leftJoin('ad_route_has_customers', function ($join) use ($id) {
                $join->on('ad_customer_has_business.id', '=', 'ad_route_has_customers.ad_customer_has_business_id')
                    ->where('ad_route_has_customers.route_id', '=', $id);
            })
            ->select(
                'cm_customer.*',
                'ad_route_has_customers.distance_km as saved_distance',
                'ad_route_has_customers.duration_minutes as saved_duration',
                'ad_route_has_customers.stop_sequence as saved_stop_sequence'
            )
            ->whereIn('cm_customer.customer_type', [CommonVariables::$customerTypeB2B, CommonVariables::$customerTypeB2C])
            ->get()
            ->map(function ($c) {
                $d = $c->businessDetails;

                if (!$d) {
                    $d = (object) [
                        'b2b_customer_type' => null,
                        'contact_person_name' => null,
                        'contact_person_phone' => null,
                        'route_id' => null,
                        // 'stop_sequence' => null, // Removed legacy usage
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
                    'stopSequence' => $c->saved_stop_sequence, // Use saved sequence from route builder table
                    'savedDistance' => $c->saved_distance,
                    'savedDuration' => $c->saved_duration,
                ];
            })
            ->filter()
            ->values();

        $googleMapsKey = config('services.google.maps_key');

        return view('agentDistribution.Partials.visualRouteBuilder', compact('route', 'agents', 'customers', 'googleMapsKey'));
    }

    /**
     * Create a new route
     */
    public function createRoute(Request $request)
    {
        try {
            $validated = $request->validate([
                'route_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'target_distance_km' => 'nullable|numeric',
                'target_duration_hours' => 'nullable|numeric',
                'agent_id' => 'nullable|integer|exists:ad_agent,id',
                'status' => 'nullable|integer|in:1,2',
            ]);

            // Create route (route_code will be auto-generated)
            $route = AdRoute::create([
                'route_name' => $validated['route_name'],
                'description' => $validated['description'] ?? null,
                'target_distance_km' => $validated['target_distance_km'] ?? null,
                'target_duration_hours' => $validated['target_duration_hours'] ?? null,
                'agent_id' => $validated['agent_id'] ?? null,
                'status' => $validated['status'] ?? 1,
            ]);

            // Load relationship
            $route->load('agent');

            return response()->json([
                'success' => true,
                'message' => 'Route created successfully',
                'route_code' => $route->route_code,
                'route' => $route,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating route: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Load single route details
     */
    public function loadRouteDetails($id)
    {
        try {
            $route = AdRoute::with('agent')->findOrFail($id);

            return response()->json([
                'success' => true,
                'route' => [
                    'id' => $route->id,
                    'route_code' => $route->route_code,
                    'route_name' => $route->route_name,
                    'description' => $route->description,
                    'target_distance_km' => $route->target_distance_km,
                    'target_duration_hours' => $route->target_duration_hours,
                    'agent_id' => $route->agent_id,
                    'agent_name' => $route->agent ? $route->agent->agent_name : null,
                    'agent_code' => $route->agent ? $route->agent->agent_code : null,
                    'status' => $route->status,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading route: ' . $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Update existing route
     */
    public function updateRoute(Request $request, $id)
    {
        try {
            $route = AdRoute::findOrFail($id);

            $validated = $request->validate([
                'route_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'target_distance_km' => 'nullable|numeric',
                'target_duration_hours' => 'nullable|numeric',
                'agent_id' => 'nullable|integer|exists:ad_agent,id',
                'status' => 'nullable|integer|in:1,2',
            ]);

            $route->update([
                'route_name' => $validated['route_name'],
                'description' => $validated['description'] ?? null,
                'target_distance_km' => $validated['target_distance_km'] ?? null,
                'target_duration_hours' => $validated['target_duration_hours'] ?? null,
                'agent_id' => $validated['agent_id'] ?? null,
                'status' => $validated['status'] ?? $route->status,
            ]);

            $route->load('agent');

            return response()->json([
                'success' => true,
                'message' => 'Route updated successfully',
                'route' => $route,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating route: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Deactivate route
     */
    public function deactivateRoute($id)
    {
        try {
            $route = AdRoute::findOrFail($id);
            $route->update(['status' => 2]); // Set to Inactive

            return response()->json([
                'success' => true,
                'message' => 'Route deactivated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deactivating route: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Customer CRUD Methods

    public function createCustomer(Request $request)
    {
        try {
            DB::beginTransaction();

            // Map frontend inputs to expected values variables
            // customerType "b2b" -> 1, "b2c" -> 2
            $customerType = $request->customerType === 'b2b' ? CommonVariables::$customerTypeB2B : CommonVariables::$customerTypeB2C;

            // Create main cm_customer record
            $customer = CmCustomer::create([
                'name' => $request->businessName,
                'customer_type' => $customerType,
                'phone' => $request->contact['phoneNumber'],
                'email' => $request->contact['email'] ?? null,
                'address' => $request->location['address'] ?? '',
                'latitude' => $request->location['latitude'] ?? null,
                'longitude' => $request->location['longitude'] ?? null,
                'created_by' => Auth::id() ?? 1,
            ]);

            // Map string constants from frontend to IDs for new table
            $b2bType = null;
            if ($request->customerType === 'b2b') {
                $b2bType = match ($request->b2bType) {
                    'wholesale' => CommonVariables::$b2bTypeWholesale,
                    'retail_shop' => CommonVariables::$b2bTypeRetail,
                    'restaurant' => CommonVariables::$b2bTypeRestaurant,
                    'hotel' => CommonVariables::$b2bTypeHotel,
                    'agent' => CommonVariables::$b2bTypeAgent,
                    default => CommonVariables::$b2bTypeOther,
                };
            }

            $visitSchedule = match ($request->visitSchedule['frequency'] ?? 'weekly') {
                'weekly' => CommonVariables::$visitScheduleWeekly,
                'bi-weekly' => CommonVariables::$visitScheduleBiWeekly,
                'monthly' => CommonVariables::$visitScheduleMonthly,
                'on-demand' => CommonVariables::$visitScheduleOnDemand,
                default => CommonVariables::$visitScheduleWeekly,
            };

            $paymentTerms = CommonVariables::$paymentTermsCash;
            if ($request->creditTerms && isset($request->creditTerms['allowCredit']) && $request->creditTerms['allowCredit']) {
                $paymentTerms = CommonVariables::$paymentTermsCredit30Days;
            }

            // Create detail record
            AdCustomerHasBusiness::create([
                'customer_id' => $customer->id,
                'contact_person_name' => $request->contact['contactPerson'] ?? $request->businessName,
                'contact_person_phone' => $request->contact['phoneNumber'],
                'contact_person_email' => $request->contact['email'] ?? null,
                'b2b_customer_type' => $b2bType,
                'payment_terms' => $paymentTerms,
                'visit_schedule' => $visitSchedule,
                'preferred_visit_days' => $request->visitSchedule['preferredDays'] ?? [], // Casts handle array
                'credit_limit' => $request->creditTerms['creditLimit'] ?? 0,
                'payment_terms_days' => $request->creditTerms['paymentTermsDays'] ?? 0,
                'agent_id' => $request->assignedAgentId ?? null,
                'route_id' => $request->assignedRouteId ?? null,
                'stop_sequence' => $request->stopSequence ?? null,
                'allow_credit' => $request->creditTerms['allowCredit'] ?? false,
                'preferred_time' => $request->visitSchedule['preferredTime'] ?? null,
                'special_instructions' => $request->specialInstructions ?? null,
                'delivery_instructions' => $request->deliveryInstructions ?? null,
                'notes' => $request->notes ?? null,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Customer created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateCustomer(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $customer = CmCustomer::findOrFail($id);

            $customerType = $request->customerType === 'b2b' ? CommonVariables::$customerTypeB2B : CommonVariables::$customerTypeB2C;

            $customer->update([
                'name' => $request->businessName,
                'customer_type' => $customerType,
                'phone' => $request->contact['phoneNumber'],
                'email' => $request->contact['email'] ?? null,
                'address' => $request->location['address'] ?? $customer->address,
                'latitude' => $request->location['latitude'] ?? $customer->latitude,
                'longitude' => $request->location['longitude'] ?? $customer->longitude,
            ]);

            // Update Details
            $b2bType = null;
            if ($request->customerType === 'b2b') {
                $b2bType = match ($request->b2bType) {
                    'wholesale' => CommonVariables::$b2bTypeWholesale,
                    'retail_shop' => CommonVariables::$b2bTypeRetail,
                    'restaurant' => CommonVariables::$b2bTypeRestaurant,
                    'hotel' => CommonVariables::$b2bTypeHotel,
                    'agent' => CommonVariables::$b2bTypeAgent,
                    default => CommonVariables::$b2bTypeOther,
                };
            }

            $visitSchedule = match ($request->visitSchedule['frequency'] ?? 'weekly') {
                'weekly' => CommonVariables::$visitScheduleWeekly,
                'bi-weekly' => CommonVariables::$visitScheduleBiWeekly,
                'monthly' => CommonVariables::$visitScheduleMonthly,
                'on-demand' => CommonVariables::$visitScheduleOnDemand,
                default => CommonVariables::$visitScheduleWeekly,
            };

            $paymentTerms = CommonVariables::$paymentTermsCash;
            if ($request->creditTerms && isset($request->creditTerms['allowCredit']) && $request->creditTerms['allowCredit']) {
                $paymentTerms = CommonVariables::$paymentTermsCredit30Days;
            }

            AdCustomerHasBusiness::updateOrCreate(
                ['customer_id' => $customer->id],
                [
                    'contact_person_name' => $request->contact['contactPerson'] ?? $request->businessName,
                    'contact_person_phone' => $request->contact['phoneNumber'],
                    'contact_person_email' => $request->contact['email'] ?? null,
                    'b2b_customer_type' => $b2bType, // Can be null if switched to B2C
                    'payment_terms' => $paymentTerms,
                    'visit_schedule' => $visitSchedule,
                    'preferred_visit_days' => $request->visitSchedule['preferredDays'] ?? [],
                    'credit_limit' => $request->creditTerms['creditLimit'] ?? 0,
                    'payment_terms_days' => $request->creditTerms['paymentTermsDays'] ?? 0,
                    'agent_id' => $request->assignedAgentId ?? null,
                    'route_id' => $request->assignedRouteId ?? null,
                    'stop_sequence' => $request->stopSequence ?? null,
                    'allow_credit' => $request->creditTerms['allowCredit'] ?? false,
                    'preferred_time' => $request->visitSchedule['preferredTime'] ?? null,
                    'special_instructions' => $request->specialInstructions ?? null,
                    'delivery_instructions' => $request->deliveryInstructions ?? null,
                    'notes' => $request->notes ?? null,
                ]
            );

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Customer updated successfully']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $customer = CmCustomer::findOrFail($id);
            $customer->delete();

            return response()->json(['success' => true, 'message' => 'Customer deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function distributorCustomerDetail($id)
    {
        $customerRecord = CmCustomer::with(['businessDetails'])->findOrFail($id);

        $details = $customerRecord->businessDetails;

        // Construct structured data for view
        $customer = [
            'id' => $customerRecord->id,
            'businessName' => $customerRecord->name,
            'tradeName' => $customerRecord->name, // Placeholder
            'customerCode' => 'CUS-' . $customerRecord->id, // Placeholder
            'customerType' => $customerRecord->customer_type == CommonVariables::$customerTypeB2B ? 'b2b' : 'b2c',
            'b2bType' => $this->getB2BTypeName($details->b2b_customer_type ?? 0),
            'isVerified' => false, // Placeholder
            'contact' => [
                'contactPerson' => $details->contact_person_name ?? $customerRecord->name,
                'phoneNumber' => $details->contact_person_phone ?? $customerRecord->phone,
                'email' => $details->contact_person_email ?? $customerRecord->email,
                'alternatePhone' => null,
            ],
            'location' => [
                'address' => $customerRecord->address,
                'city' => 'Colombo', // Placeholder
                'district' => 'Western', // Placeholder
                'latitude' => 6.9271,
                'longitude' => 79.8612,
            ],
            'stopSequence' => 1, // Placeholder
            'visitSchedule' => [
                'frequency' => $this->getVisitScheduleName($details->visit_schedule ?? 1),
                'preferredDays' => $details->preferred_visit_days ?? [],
                'visitCount' => 0,
                'lastVisitDate' => null, // null is handled in view check
                'nextScheduledVisit' => date('Y-m-d', strtotime('+1 week')),
            ],
            'specialInstructions' => null,
            'deliveryInstructions' => null,
            'currentBalance' => 0,
            'creditDays' => 0,
            'creditTerms' => [
                'paymentTermsDays' => $details->payment_terms_days ?? 30,
                'creditLimit' => $details->credit_limit ?? 0,
            ],
            'invoiceAging' => [
                'current' => 0,
                'days30' => 0,
                'days60' => 0,
                'days90Plus' => 0,
            ],
            'totalSales' => 0,
            'totalOrders' => 0,
            'averageOrderValue' => 0,
            'lastOrderDate' => date('Y-m-d'),
            'lastPaymentDate' => date('Y-m-d'),
            'status' => 'active',
            'createdAt' => $customerRecord->created_at,
            'updatedAt' => $customerRecord->updated_at,
        ];

        // Mock Related Data
        $agent = ['id' => 1, 'agentName' => 'Assigned Agent', 'agentCode' => 'AGT001'];
        $route = ['id' => 1, 'routeName' => 'General Route', 'routeCode' => 'R001'];

        $orders = []; // Placeholder
        $payments = []; // Placeholder
        $visits = []; // Placeholder

        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get()
            ->map(function ($a) {
                return ['id' => $a->id, 'agentName' => $a->agent_name, 'agentCode' => $a->agent_code];
            });

        $routes = AdRoute::where('status', 1)->get()
            ->map(function ($r) {
                return ['id' => $r->id, 'routeName' => $r->route_name];
            });

        $b2bTypes = [
            'wholesale' => $this->getB2BTypeName(CommonVariables::$b2bTypeWholesale),
            'retail_shop' => $this->getB2BTypeName(CommonVariables::$b2bTypeRetail),
            'restaurant' => $this->getB2BTypeName(CommonVariables::$b2bTypeRestaurant),
            'hotel' => $this->getB2BTypeName(CommonVariables::$b2bTypeHotel),
            'agent' => $this->getB2BTypeName(CommonVariables::$b2bTypeAgent),
            'other' => $this->getB2BTypeName(CommonVariables::$b2bTypeOther),
        ];

        return view('agentDistribution.customerDetail', compact('customer', 'agent', 'route', 'orders', 'payments', 'visits', 'agents', 'routes', 'b2bTypes'));
    }

    public function saveBuilderRoute(Request $request)
    {
        try {
            DB::beginTransaction();

            $routeId = $request->route_id;

            // Create or update route
            if ($routeId) {
                $route = AdRoute::findOrFail($routeId);
                $route->update([
                    'route_name' => $request->route_name,
                    'agent_id' => $request->agent_id,
                    'target_distance_km' => $request->total_distance_km,
                ]);
            } else {
                $route = AdRoute::create([
                    'route_name' => $request->route_name,
                    'agent_id' => $request->agent_id,
                    'target_distance_km' => $request->total_distance_km,
                    'status' => 1, // Active
                ]);
            }

            // Delete existing route-customer assignments
            DB::table('ad_route_has_customers')
                ->where('route_id', $route->id)
                ->delete();

            // Save new assignments with distance and duration
            foreach ($request->stops as $stop) {
                DB::table('ad_route_has_customers')->insert([
                    'route_id' => $route->id,
                    'customer_id' => $stop['customer_id'],
                    'stop_sequence' => $stop['stop_sequence'],
                    'distance_km' => $stop['distance_km'],
                    'duration_minutes' => $stop['duration_minutes'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Also update customer's business details
                AdCustomerHasBusiness::where('customer_id', $stop['customer_id'])
                    ->update([
                        'route_id' => $route->id,
                        'stop_sequence' => $stop['stop_sequence'],
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Route saved successfully',
                'route' => $route,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // --- Daily Loads Methods ---

    public function searchProductsForLoad(Request $request)
    {
        $search = $request->input('query');

        $products = PmProductItem::where('product_name', 'LIKE', "%{$search}%")
            ->orWhere('id', 'LIKE', "%{$search}%")
            ->limit(20)
            ->get(['id', 'product_name']);

        $formatted = $products->map(function ($product) {
            $price = $this->calculateStockPrice($product->id);

            return [
                'id' => $product->id,
                'text' => $product->product_name,
                'product_name' => $product->product_name,
                'price_raw' => $price,
            ];
        });

        return response()->json($formatted);
    }

    public function getProductPrice(Request $request)
    {
        $productId = $request->input('product_id');
        $price = $this->calculateStockPrice($productId);

        return response()->json([
            'success' => true,
            'price' => $price,
        ]);
    }

    private function calculateStockPrice($productId)
    {
        // Fetch average selling price from stm_stock where quantity > 0
        $avgPrice = DB::table('stm_stock')
            ->where('pm_product_item_id', $productId)
            ->where('selling_price', '>', 0)
            ->avg('selling_price');

        return $avgPrice ? round($avgPrice, 2) : 0;
    }

    public function storeDailyLoad(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'agent_id' => 'required|exists:ad_agent,id',
                'route_id' => 'required|exists:ad_routes,id',
                'load_date' => 'required|date',
                'status' => 'required|integer',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:pm_product_item,id',
                'items.*.quantity' => 'required|numeric|min:0.001',
                'items.*.price' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            // Create Load Header
            $load = AdDailyLoad::create([
                'agent_id' => $validated['agent_id'],
                'route_id' => $validated['route_id'],
                'load_date' => $validated['load_date'],
                'status' => $validated['status'],
                'is_mark_as_loaded' => false,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create Items
            foreach ($validated['items'] as $item) {
                $qty = $item['quantity'];
                $price = $item['price'];
                $total = $qty * $price;

                AdDailyLoadItem::create([
                    'daily_load_id' => $load->id,
                    'product_item_id' => $item['id'],
                    'quantity' => $qty,
                    'price' => $price,
                    'total_value' => $total,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Daily Load created successfully',
                'load_id' => $load->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error creating load: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function markAsLoaded(Request $request)
    {
        try {
            $loadId = $request->input('load_id');
            $load = AdDailyLoad::findOrFail($loadId);

            $load->update([
                'status' => CommonVariables::$dailyLoadStatusLoaded,
                'is_mark_as_loaded' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Load marked as loaded successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function monthlyTargetsIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)->get();
        $productItems = PmProductItem::with('category')->where('status', 1)->get(['id', 'product_name', 'pm_product_category_id']);
        $productCategories = PmProductCategory::where('is_active', true)->get(['id', 'category_name', 'category_code']);
        
        // Fetch existing targets for the list view, ordered by most recent first
        $monthlyTargets = AdAgentMonthlyTarget::with('agent')
            ->orderBy('target_year', 'desc')
            ->orderBy('target_month', 'desc')
            ->get();

        return view('agentDistribution.monthlyTargets', compact('agents', 'productItems', 'productCategories', 'monthlyTargets'));
    }

    public function getMonthlyTargets(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:ad_agent,id',
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
        ]);

        $monthlyTarget = AdAgentMonthlyTarget::with(['categoryTargets.category', 'itemTargets.item'])
            ->where('agent_id', $request->agent_id)
            ->where('target_year', $request->year)
            ->where('target_month', $request->month)
            ->first();

        if (!$monthlyTarget) {
            return response()->json([
                'success' => true,
                'data' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $monthlyTarget->id,
                'monthly_sales_target' => $monthlyTarget->monthly_sales_target,
                'base_salary' => $monthlyTarget->base_salary,
                'commission_rate' => $monthlyTarget->commission_rate,
                'invoicing_commission_rate' => $monthlyTarget->invoicing_commission_rate,
                'target_commission_rate' => $monthlyTarget->target_commission_rate,
                'achievement_threshold' => $monthlyTarget->achievement_threshold,
                'reduced_target_commission_rate' => $monthlyTarget->reduced_target_commission_rate,
                'status' => $monthlyTarget->status,
                'category_targets' => $monthlyTarget->categoryTargets->map(function ($ct) {
                    return [
                        'pm_product_category_id' => $ct->pm_product_category_id,
                        'category_name' => $ct->category->category_name ?? 'Unknown',
                        'target_amount' => $ct->target_amount,
                        'target_percentage' => $ct->target_percentage,
                    ];
                }),
                'item_targets' => $monthlyTarget->itemTargets->map(function ($it) {
                    return [
                        'pm_product_item_id' => $it->pm_product_item_id,
                        'product_name' => $it->item->product_name ?? 'Unknown',
                        'target_amount' => $it->target_amount,
                        'target_qty' => $it->target_qty,
                        'target_percentage' => $it->target_percentage,
                    ];
                }),
            ]
        ]);
    }

    public function saveMonthlyTargets(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:ad_agent,id',
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
            'monthly_sales_target' => 'nullable|numeric',
            'base_salary' => 'nullable|numeric',
            'commission_rate' => 'nullable|numeric',
            'invoicing_commission_rate' => 'nullable|numeric',
            'target_commission_rate' => 'nullable|numeric',
            'achievement_threshold' => 'nullable|numeric',
            'reduced_target_commission_rate' => 'nullable|numeric',
            'category_targets' => 'nullable|array',
            'item_targets' => 'nullable|array',
            'status' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $monthlyTarget = AdAgentMonthlyTarget::updateOrCreate(
                [
                    'agent_id' => $request->agent_id,
                    'target_year' => $request->year,
                    'target_month' => $request->month,
                ],
                [
                    'monthly_sales_target' => $request->monthly_sales_target ?? 0.00,
                    'base_salary' => $request->base_salary,
                    'commission_rate' => $request->commission_rate,
                    'invoicing_commission_rate' => $request->invoicing_commission_rate ?? 15.00,
                    'target_commission_rate' => $request->target_commission_rate ?? 5.00,
                    'achievement_threshold' => $request->achievement_threshold ?? 80.00,
                    'reduced_target_commission_rate' => $request->reduced_target_commission_rate ?? 4.00,
                    'status' => $request->status ?? 1,
                    'updated_by' => Auth::id(),
                    'created_by' => AdAgentMonthlyTarget::where([
                        'agent_id' => $request->agent_id,
                        'target_year' => $request->year,
                        'target_month' => $request->month,
                    ])->exists() ? null : Auth::id(),
                ]
            );

            // Save Category Targets
            $monthlyTarget->categoryTargets()->delete();
            if ($request->has('category_targets') && is_array($request->category_targets)) {
                foreach ($request->category_targets as $ct) {
                    if (!empty($ct['pm_product_category_id'])) {
                        AdAgentHasCategoryTargets::create([
                            'monthly_target_id' => $monthlyTarget->id,
                            'pm_product_category_id' => $ct['pm_product_category_id'],
                            'target_amount' => $ct['target_amount'] ?? null,
                            'target_percentage' => $ct['target_percentage'] ?? null,
                            'is_active' => true,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            // Save Item Targets
            $monthlyTarget->itemTargets()->delete();
            if ($request->has('item_targets') && is_array($request->item_targets)) {
                foreach ($request->item_targets as $it) {
                    if (!empty($it['pm_product_item_id'])) {
                        AdAgentHasItemTargets::create([
                            'monthly_target_id' => $monthlyTarget->id,
                            'pm_product_item_id' => $it['pm_product_item_id'],
                            'target_amount' => $it['target_amount'] ?? null,
                            'target_qty' => $it['target_qty'] ?? null,
                            'target_percentage' => $it['target_percentage'] ?? null,
                            'is_active' => true,
                            'created_by' => Auth::id(),
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Monthly targets saved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving monthly targets: ' . $e->getMessage()
            ], 500);
        }
    }

    public function quickSaveAgent(Request $request)
    {
        $request->validate([
            'agent_name' => 'required|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        try {
            $agent = AdAgent::create([
                'agent_name' => $request->agent_name,
                'phone' => $request->contact_phone,
                'agent_type' => 3, // Default to Credit Based
                'status' => 1, // Active
                'outstanding_balance' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Agent created successfully',
                'agent' => [
                    'id' => $agent->id,
                    'agent_name' => $agent->agent_name,
                    'agent_code' => $agent->agent_code
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePaymentStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:ad_agent_has_monthly_targets,id',
            'payment_status' => 'required|integer|in:0,1,2'
        ]);

        try {
            $target = AdAgentMonthlyTarget::findOrFail($request->id);
            $target->update(['payment_status' => $request->payment_status]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function agentOverviewIndex()
    {
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->get(['id', 'agent_name', 'agent_code']);

        return view('agentDistribution.agentOverview', compact('agents'));
    }

    public function getAgentOverviewData($id)
    {
        try {
            $agent = AdAgent::with('bankAccounts')->findOrFail($id);
            
            // 1. Basic Stats
            $stats = [
                'total_sales' => (double) $agent->total_sales,
                'outstanding_balance' => (double) $agent->outstanding_balance,
                'total_collections' => (double) $agent->total_collections,
                'credit_limit' => (double) $agent->credit_limit,
            ];

            // 2. Routes
            $routes = AdRoute::where('agent_id', $id)
                ->withCount('customers')
                ->get();

            // 3. Daily Loads (Enhanced)
            $dailyLoads = AdDailyLoad::where('agent_id', $id)
                ->with([
                    'route', 
                    'driver', 
                    'vehicle', 
                    'supervisor',
                    'items.product',
                    'invoices' => function($q) {
                        $q->with(['business', 'items.product', 'newReturnItems.product']);
                    }
                ])
                ->orderBy('load_date', 'desc')
                ->limit(10)
                ->get();

            // 4. Team (Drivers & Supervisors)
            $drivers = DmDriver::where('agent_id', $id)->get();
            $supervisors = SmSuperviser::where('agent_id', $id)->get();

            // 5. Vehicles
            $vehicles = VmVehicle::where('agent_id', $id)->get();

            // 6. Customers (Enhanced with Sales & Outstanding)
            $customers = AdCustomerHasBusiness::where('agent_id', $id)
                ->with(['customer'])
                ->get()
                ->map(function($c) {
                    $invoiceStats = DB::table('ad_cubusiness_has_invoice')
                        ->where('ad_customer_has_business_id', $c->id)
                        ->where('status', 1)
                        ->select(
                            DB::raw('SUM(net_price) as total_sales'),
                            DB::raw('SUM(net_price - total_amount_paid) as outstanding'),
                            DB::raw('MAX(created_at) as last_invoice')
                        )->first();

                    $c->total_sales = (double) ($invoiceStats->total_sales ?? 0);
                    $c->outstanding = (double) ($invoiceStats->outstanding ?? 0);
                    $c->last_invoice = $invoiceStats->last_invoice;
                    return $c;
                });

            // 7. Order Requests (Enhanced)
            $orders = StmOrderRequest::where('agent_id', $id)
                ->with(['customer', 'orderProducts.productItem'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // 8. Monthly Sales Trend (Current Year)
            $salesTrend = DB::table('ad_cubusiness_has_invoice as i')
                ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
                ->where('l.agent_id', $id)
                ->where('i.status', 1)
                ->whereYear('i.created_at', date('Y'))
                ->select(
                    DB::raw('MONTH(i.created_at) as month'),
                    DB::raw('SUM(i.net_price) as sales')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // 9. Top Products
            $topProducts = DB::table('ad_cubusiness_has_product_item as pi')
                ->join('ad_cubusiness_has_invoice as i', 'pi.ad_cubusiness_has_invoice_id', '=', 'i.id')
                ->join('ad_daily_loads as l', 'i.ad_daily_load_id', '=', 'l.id')
                ->join('pm_product_item as p', 'pi.pm_product_item_id', '=', 'p.id')
                ->where('l.agent_id', $id)
                ->where('i.status', 1)
                ->select(
                    'p.product_name',
                    DB::raw('SUM(pi.quantity) as total_qty'),
                    DB::raw('SUM(pi.total_price) as total_value')
                )
                ->groupBy('p.id', 'p.product_name')
                ->orderBy('total_value', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'status' => true,
                'data' => [
                    'agent' => $agent,
                    'stats' => $stats,
                    'routes' => $routes,
                    'dailyLoads' => $dailyLoads,
                    'drivers' => $drivers,
                    'supervisors' => $supervisors,
                    'vehicles' => $vehicles,
                    'customers' => $customers,
                    'orders' => $orders,
                    'salesTrend' => $salesTrend,
                    'topProducts' => $topProducts,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
