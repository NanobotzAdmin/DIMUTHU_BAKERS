<?php

namespace App\Http\Controllers;

use App\CommonVariables;
use App\Models\AdAgent;
use App\Models\AdAgentHasBankAccount;
use App\Models\AdCustomerHasBusiness;
use App\Models\AdDailyLoad;
use App\Models\AdDailyLoadItem;
use App\Models\AdRoute;
use App\Models\CmCustomer;
use App\Models\PmProductItem;
use App\Models\UmUser;
use Illuminate\Http\Request;
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
        $agents = AdAgent::with('primaryBankAccount')->get()->map(function ($agent) {
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
                'bankName' => $agent->primaryBankAccount->bank_name ?? null,
                'bankAccountNumber' => $agent->primaryBankAccount->account_number ?? null,
                'bankBranch' => $agent->primaryBankAccount->branch ?? null,
                'outstandingBalance' => $agent->outstanding_balance,
                'totalSales' => $agent->total_sales,
                'totalCollections' => $agent->total_collections,
            ];
        });

        return view('agentDistribution.agentManagement', compact('agents'));
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
                'loadNumber' => 'LOAD-'.$load->id, // Simple ID based number for now
                'agentId' => $load->agent_id,
                'status' => $this->getLoadStatusLabel($load->status),
                'status_code' => $load->status,
                'loadDate' => $load->load_date->format('Y-m-d'),
                'totalQuantity' => $totalQty,
                'totalValue' => $totalVal,
                'items' => $load->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'productName' => $item->product ? $item->product->product_name : 'Unknown',
                        'loadedQuantity' => $item->quantity,
                        'unitPrice' => $item->price,
                    ];
                })->toArray(),
                'notes' => $load->notes,
                'isMarkedAsLoaded' => $load->is_mark_as_loaded,
            ];
        })->toArray();

        return view('agentDistribution.dailyLoads', compact('agents', 'routes', 'loads'));
    }

    private function getLoadStatusLabel($status)
    {
        switch ($status) {
            case CommonVariables::$dailyLoadStatusDraft: return 'draft';
            case CommonVariables::$dailyLoadStatusLoaded: return 'loaded';
            case CommonVariables::$dailyLoadStatusCompleted: return 'completed';
            default: return 'draft';
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
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'status' => 'active'],
        ];

        // Mock Settlements - Last 30 days mixed data
        $settlements = [];
        $baseDate = date('Y-m-d');

        // Helper for random settlements
        for ($i = 0; $i < 40; $i++) {
            $agent = $agents[rand(0, 2)];
            $status = ['pending', 'reviewed', 'approved', 'disputed'][rand(0, 3)];
            $sales = rand(10000, 30000);

            // Variance logic
            $expected = $sales * 0.6; // Assuming 60% cash sales
            $variance = 0;
            if (rand(0, 100) > 80) {
                $variance = rand(-500, 200);
            } // 20% chance of variance

            $actual = $expected + $variance;

            $settlements[] = [
                'id' => "stl_$i",
                'agentId' => $agent['id'],
                'settlementNumber' => 'SET-'.date('Ymd', strtotime("$baseDate -$i days"))."-$i",
                'settlementDate' => date('Y-m-d', strtotime("$baseDate -$i days")),
                'status' => $status,
                'totalSales' => $sales,
                'expectedCash' => $expected,
                'actualCash' => $actual,
                'cashVariance' => $variance,
                'totalCollections' => rand(0, 5000),
                'returnedValue' => rand(0, 1000),
                'amountDueToBakery' => $actual, // Simplified
                'commissionEarned' => $sales * 0.05,
                'varianceNotes' => $variance != 0 ? 'Cash mismatch detected' : '',
                'notes' => '',
                'submittedAt' => date('Y-m-d H:i:s', strtotime("$baseDate -$i days 17:00:00")),
                'reviewedAt' => $status != 'pending' ? date('Y-m-d H:i:s', strtotime("$baseDate -$i days 18:00:00")) : null,
                'approvedAt' => $status == 'approved' ? date('Y-m-d H:i:s', strtotime("$baseDate -$i days 19:00:00")) : null,
            ];
        }

        // Ensure at least one pending with variance for testing
        $settlements[0]['status'] = 'pending';
        $settlements[0]['cashVariance'] = -250;
        $settlements[0]['varianceNotes'] = 'Shortage detected';

        return view('agentDistribution.settlementList', compact('agents', 'settlements'));
    }

    public function settlementDetail($id)
    {
        // Mocking single settlement retrieval
        $idParts = explode('_', $id);
        $agentId = 'agt_1'; // Default Fallback

        // Basic Mock Settlement
        $settlement = [
            'id' => $id,
            'settlementNumber' => 'SET-20260113-001',
            'agentId' => $agentId,
            'settlementDate' => date('Y-m-d'),
            'status' => 'pending',
            'totalSales' => 25000,
            'cashSales' => 15000,
            'creditSales' => 5000,
            'chequeSales' => 5000,
            'expectedCash' => 15000,
            'actualCash' => 15000,
            'cashVariance' => 0,
            'totalCollections' => 2500,
            'returnedValue' => 500,
            'amountDueToBakery' => 17000, // actualCash + collections - returns (simplified)
            'commissionEarned' => 1250,
            'loadedValue' => 30000,
            'submittedAt' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'varianceNotes' => '',
            'notes' => 'Sales were good today.',
        ];

        $agent = ['id' => $agentId, 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'agentType' => 'Permanent', 'commissionRate' => 5.0];
        $load = ['loadNumber' => 'LOAD-001', 'loadDate' => date('Y-m-d'), 'totalQuantity' => 150, 'totalValue' => 30000];

        $sales = [
            ['id' => 'sale_1', 'invoiceNumber' => 'INV-001', 'customerName' => 'City Cafe', 'saleDate' => date('Y-m-d H:i:s'), 'totalAmount' => 5000, 'paymentMethod' => 'cash', 'totalQuantity' => 20, 'subtotal' => 5000],
            ['id' => 'sale_2', 'invoiceNumber' => 'INV-002', 'customerName' => 'Metro Mart', 'saleDate' => date('Y-m-d H:i:s'), 'totalAmount' => 10000, 'paymentMethod' => 'credit', 'totalQuantity' => 50, 'subtotal' => 10000],
        ];

        $collections = [
            ['id' => 'col_1', 'receiptNumber' => 'REC-001', 'customerName' => 'Old Town Store', 'collectionDate' => date('Y-m-d H:i:s'), 'amount' => 2500, 'paymentMethod' => 'cash', 'notes' => 'Previous balance'],
        ];

        $returns = [
            ['id' => 'ret_1', 'returnNumber' => 'RET-001', 'totalQuantity' => 10, 'totalValue' => 500, 'goodConditionValue' => 200, 'damagedValue' => 300, 'notes' => 'Expired items'],
        ];

        return view('agentDistribution.settlementDetail', compact('settlement', 'agent', 'load', 'sales', 'collections', 'returns'));
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
                'settlementNumber' => 'SET-'.date('Ymd', strtotime($date))."-$i",
                'settlementDate' => $date,
                'status' => $isPosted ? 'gl_posted' : 'approved',
                'glPosted' => $isPosted,
                'glJournalEntryId' => $isPosted ? 'JE-AGT-'.strtotime($date)."-$i" : null,
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
        return view('errors.under-development');
    }

    public function commissionPaymentIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'commissionRate' => 5.0, 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'commissionRate' => 4.5, 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'commissionRate' => 5.0, 'status' => 'active'],
        ];

        // Mock Settlements for calculating commissions (Last 90 days)
        $settlements = [];
        $startDate = date('Y-m-d', strtotime('-90 days'));

        for ($i = 0; $i < 90; $i++) {
            $date = date('Y-m-d', strtotime("$startDate +$i days"));

            foreach ($agents as $agent) {
                // Generates settlements
                if (rand(1, 100) > 30) {
                    $sales = rand(10000, 30000);
                    $settlements[] = [
                        'id' => "stl_{$agent['id']}_$i",
                        'agentId' => $agent['id'],
                        'settlementNumber' => 'SET-'.date('Ymd', strtotime($date))."-{$agent['id']}",
                        'settlementDate' => $date,
                        'totalSales' => $sales,
                        'status' => 'approved',
                        'commissionEarned' => $sales * ($agent['commissionRate'] / 100),
                    ];
                }
            }
        }

        return view('agentDistribution.commissionPayments', compact('agents', 'settlements'));
    }

    public function commissionStatementsIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'commissionRate' => 5.0, 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'commissionRate' => 4.5, 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'commissionRate' => 5.0, 'status' => 'active'],
        ];

        // Mock Settlements for generating statements (Last 6 months)
        $settlements = [];
        $startDate = date('Y-m-d', strtotime('-6 months'));

        for ($i = 0; $i < 180; $i++) {
            $date = date('Y-m-d', strtotime("$startDate +$i days"));

            foreach ($agents as $agent) {
                // Generates settlements
                if (rand(1, 100) > 30) {
                    $sales = rand(15000, 25000);
                    $settlements[] = [
                        'id' => "stl_{$agent['id']}_$i",
                        'agentId' => $agent['id'],
                        'settlementNumber' => 'SET-'.date('Ymd', strtotime($date))."-{$agent['id']}",
                        'settlementDate' => $date,
                        'totalSales' => $sales,
                        'cashSales' => $sales * 0.6, // 60% cash
                        'creditSales' => $sales * 0.3, // 30% credit
                        'chequeSales' => $sales * 0.1, // 10% cheque
                        'status' => 'approved',
                        'commissionEarned' => $sales * ($agent['commissionRate'] / 100),
                    ];
                }
            }
        }

        return view('agentDistribution.commissionStatements', compact('agents', 'settlements'));
    }

    public function agentAnalyticsIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'status' => 'active'],
            ['id' => 'agt_4', 'agentName' => 'Emily Brown', 'agentCode' => 'AGT004', 'status' => 'active'],
            ['id' => 'agt_5', 'agentName' => 'David Wilson', 'agentCode' => 'AGT005', 'status' => 'active'],
        ];

        // Mock Settlements - generate enough for 90 days analysis
        $settlements = [];
        $startDate = date('Y-m-d', strtotime('-90 days'));

        // Helper to generate random settlements
        for ($i = 0; $i < 90; $i++) {
            $date = date('Y-m-d', strtotime("$startDate +$i days"));

            foreach ($agents as $agent) {
                // Not every agent works every day - 80% chance
                if (rand(1, 100) > 20) {
                    $sales = rand(10000, 30000);
                    $variance = 0;

                    // Simulate occasional variance
                    if (rand(1, 100) > 90) {
                        $variance = rand(-500, 200);
                    }

                    $settlements[] = [
                        'id' => "stl_{$agent['id']}_$i",
                        'agentId' => $agent['id'],
                        'settlementDate' => $date,
                        'totalSales' => $sales,
                        'actualCash' => $sales + $variance,
                        'cashVariance' => $variance,
                        'commissionEarned' => $sales * 0.05, // 5% comm
                    ];
                }
            }
        }

        // Mock Sales for payment method distribution
        $sales = [];
        // Generate a sample set, mapped to settlements implicitly by date/agent logic reuse if needed,
        // but for analytics report we just need aggregate stats.
        // Let's generate a pool of sales records.
        for ($i = 0; $i < 500; $i++) {
            $date = date('Y-m-d', strtotime("-$i hours")); // Spread over recent time
            $sales[] = [
                'id' => "sale_$i",
                'agentId' => $agents[rand(0, 4)]['id'],
                'saleDate' => $date, // This might need to align with date filters more carefully in JS
                'paymentMethod' => ['cash', 'cash', 'cash', 'credit', 'credit', 'cheque'][rand(0, 5)],
                'totalAmount' => rand(500, 5000),
            ];
        }
        // *Correction*: ensuring date range cover for sales to match filters
        $sales = [];
        for ($i = 0; $i < 90; $i++) {
            $date = date('Y-m-d', strtotime("$startDate +$i days"));
            // avg 10 sales per day per agent
            foreach ($agents as $agent) {
                for ($j = 0; $j < 5; $j++) {
                    $sales[] = [
                        'id' => "sale_{$agent['id']}_{$i}_{$j}",
                        'agentId' => $agent['id'],
                        'saleDate' => $date,
                        'paymentMethod' => ['cash', 'cash', 'cash', 'credit', 'credit', 'cheque'][rand(0, 5)],
                        'totalAmount' => rand(500, 5000),
                    ];
                }
            }
        }

        return view('agentDistribution.agentAnalytics', compact('agents', 'settlements', 'sales'));
    }

    public function financialDashboardIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'commissionRate' => 5.0, 'status' => 'active'],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'commissionRate' => 4.5, 'status' => 'active'],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'commissionRate' => 5.0, 'status' => 'active'],
        ];

        // Mock Settlements (Last 12 months data for trends)
        $settlements = [];
        // Generate some realistic looking data
        for ($i = 0; $i < 12; $i++) {
            $month = date('Y-m', strtotime("-$i months"));

            // Agent 1 Data
            $settlements[] = [
                'id' => "stl_a1_$i",
                'agentId' => 'agt_1',
                'settlementNumber' => "SET-A1-$i",
                'settlementDate' => "$month-15",
                'totalSales' => rand(140000, 160000),
                'cashSales' => rand(80000, 90000),
                'creditSales' => rand(50000, 60000),
                'chequeSales' => 10000,
                'actualCash' => rand(80000, 90000),
                'cashVariance' => 0,
                'commissionEarned' => rand(7000, 8000),
                'status' => 'approved',
            ];

            // Agent 2 Data
            $settlements[] = [
                'id' => "stl_a2_$i",
                'agentId' => 'agt_2',
                'settlementNumber' => "SET-A2-$i",
                'settlementDate' => "$month-12",
                'totalSales' => rand(90000, 110000),
                'cashSales' => rand(50000, 60000),
                'creditSales' => rand(30000, 40000),
                'chequeSales' => 5000,
                'actualCash' => rand(49900, 59900),
                'cashVariance' => -rand(0, 200), // Some variance
                'commissionEarned' => rand(4000, 5000),
                'status' => 'approved',
            ];
        }

        // Transaction counts mock (sales)
        $sales = [];
        // We'll simulate transaction counts in JS calculations or just pass summary data if needed,
        // but let's pass a small sample to show structure
        $sales = [
            ['id' => 'sale_1', 'agentId' => 'agt_1', 'saleDate' => date('Y-m-d'), 'amount' => 1500],
            ['id' => 'sale_2', 'agentId' => 'agt_1', 'saleDate' => date('Y-m-d'), 'amount' => 2500],
        ];

        $commissionPayments = [
            ['id' => 'cp_1', 'agentId' => 'agt_1', 'periodEnd' => date('Y-m-d', strtotime('-1 month')), 'grossCommission' => 8000, 'deductions' => ['tax' => 100, 'advances' => 0, 'other' => 0], 'netCommission' => 7900, 'paymentStatus' => 'paid'],
            ['id' => 'cp_2', 'agentId' => 'agt_1', 'periodEnd' => date('Y-m-d'), 'grossCommission' => 8500, 'deductions' => ['tax' => 120, 'advances' => 500, 'other' => 0], 'netCommission' => 7880, 'paymentStatus' => 'pending'],
            ['id' => 'cp_3', 'agentId' => 'agt_2', 'periodEnd' => date('Y-m-d', strtotime('-1 month')), 'grossCommission' => 4500, 'deductions' => ['tax' => 50, 'advances' => 0, 'other' => 0], 'netCommission' => 4450, 'paymentStatus' => 'paid'],
        ];

        return view('agentDistribution.financialDashboard', compact('agents', 'settlements', 'sales', 'commissionPayments'));
    }

    public function agentDistributionReportIndex()
    {
        $agents = [
            ['id' => 'agt_1', 'agentName' => 'John Doe', 'agentCode' => 'AGT001', 'commissionRate' => 5.0],
            ['id' => 'agt_2', 'agentName' => 'Sarah Smith', 'agentCode' => 'AGT002', 'commissionRate' => 4.5],
            ['id' => 'agt_3', 'agentName' => 'Mike Johnson', 'agentCode' => 'AGT003', 'commissionRate' => 5.0],
        ];

        // Mock Settlements Data (Last 30 days)
        $settlements = [
            // Agent 1
            ['id' => 'stl_1', 'agentId' => 'agt_1', 'settlementNumber' => 'SET-001', 'settlementDate' => date('Y-m-d'), 'totalSales' => 15000, 'cashSales' => 10000, 'creditSales' => 5000, 'chequeSales' => 0, 'actualCash' => 10000, 'cashVariance' => 0, 'totalCollections' => 2000, 'returnedValue' => 0, 'commissionEarned' => 750, 'status' => 'approved', 'varianceNotes' => ''],
            ['id' => 'stl_2', 'agentId' => 'agt_1', 'settlementNumber' => 'SET-004', 'settlementDate' => date('Y-m-d', strtotime('-1 day')), 'totalSales' => 18000, 'cashSales' => 12000, 'creditSales' => 6000, 'chequeSales' => 0, 'actualCash' => 12000, 'cashVariance' => 0, 'totalCollections' => 1500, 'returnedValue' => 100, 'commissionEarned' => 900, 'status' => 'approved', 'varianceNotes' => ''],

            // Agent 2 (Shortage example)
            ['id' => 'stl_3', 'agentId' => 'agt_2', 'settlementNumber' => 'SET-002', 'settlementDate' => date('Y-m-d'), 'totalSales' => 12000, 'cashSales' => 8000, 'creditSales' => 4000, 'chequeSales' => 0, 'actualCash' => 7900, 'cashVariance' => -100, 'totalCollections' => 0, 'returnedValue' => 50, 'commissionEarned' => 540, 'status' => 'reviewed', 'varianceNotes' => 'Miscalculation in change'],

            // Agent 3 (Surplus example)
            ['id' => 'stl_4', 'agentId' => 'agt_3', 'settlementNumber' => 'SET-003', 'settlementDate' => date('Y-m-d'), 'totalSales' => 20000, 'cashSales' => 15000, 'creditSales' => 5000, 'chequeSales' => 0, 'actualCash' => 15050, 'cashVariance' => 50, 'totalCollections' => 5000, 'returnedValue' => 200, 'commissionEarned' => 1000, 'status' => 'pending', 'varianceNotes' => 'Found extra cash'],
        ];

        // Simple mock for transaction counts
        $sales = [
            ['id' => 'sale_1', 'agentId' => 'agt_1', 'saleDate' => date('Y-m-d'), 'amount' => 500],
            ['id' => 'sale_2', 'agentId' => 'agt_1', 'saleDate' => date('Y-m-d'), 'amount' => 1000],
            ['id' => 'sale_3', 'agentId' => 'agt_2', 'saleDate' => date('Y-m-d'), 'amount' => 800],
            ['id' => 'sale_4', 'agentId' => 'agt_3', 'saleDate' => date('Y-m-d'), 'amount' => 2500],
            // More historic data simulated by JS logic if needed, or we just rely on aggregated settlement data for reports usually
        ];

        $collections = [];
        $returns = [];

        return view('agentDistribution.reports', compact('agents', 'settlements', 'sales', 'collections', 'returns'));
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
                    'productName' => ['Bun', 'Bread', 'Cake', 'Pastry', 'Muffin'][rand(0, 4)].' '.chr(65 + rand(0, 5)),
                    'quantity' => $qty,
                    'unitPrice' => $price,
                    'total' => $total,
                ];
            }

            $orders[] = [
                'id' => "ord_$i",
                'orderNumber' => 'ORD-'.date('Ymd')."-$i",
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
                    'invoiceNumber' => 'INV-'.date('Ymd').'-'.rand(100, 999),
                    'allocatedAmount' => $amount * 0.6,
                ];
                $allocations[] = [
                    'invoiceNumber' => 'INV-'.date('Ymd').'-'.rand(100, 999),
                    'allocatedAmount' => $amount * 0.4,
                ];
            }

            $payments[] = [
                'id' => "pay_$i",
                'receiptNumber' => 'REC-'.date('Ymd')."-$i",
                'date' => date('Y-m-d', strtotime("-$i months")),
                'amount' => $amount,
                'method' => ['cash', 'cheque', 'bank_transfer', 'card'][rand(0, 3)],
                'status' => 'verified',
                'agentName' => 'John Doe',
                'reference' => rand(0, 1) ? 'REF-'.rand(1000, 9999) : null,
                'allocations' => $allocations,
                'notes' => rand(0, 1) ? 'Payment received with thanks' : null,
            ];
        }

        $visits = [];
        for ($i = 0; $i < 10; $i++) {
            $visitDate = date('Y-m-d', strtotime("-$i days"));
            $checkIn = date('H:i', strtotime('09:00 + '.($i * 30).' minutes'));
            $checkOut = date('H:i', strtotime($checkIn.' + '.rand(10, 45).' minutes'));

            $status = ['completed', 'skipped', 'in_progress'][rand(0, 2)];
            $orderPlaced = rand(0, 1);
            $paymentCollected = rand(0, 1);

            $visits[] = [
                'id' => "vis_$i",
                'visitNumber' => 'VISIT-'.date('Ymd')."-$i",
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
                'base_salary' => 'nullable|numeric|min:0',
                'commission_rate' => 'nullable|numeric|min:0|max:100',
                'credit_limit' => 'nullable|numeric|min:0',
                'credit_period_days' => 'nullable|integer|min:0',
                'bank_accounts' => 'required|array|min:1',
                'bank_accounts.*.bank_name' => 'required|string|max:255',
                'bank_accounts.*.account_number' => 'required|string|max:50',
                'bank_accounts.*.branch' => 'nullable|string|max:255',
                'bank_accounts.*.is_primary' => 'nullable|boolean',
            ]);

            // Create user account first
            $defaultPassword = 123456;
            $userName = strtolower(str_replace(' ', '', $validated['agent_name'])).'_agent';

            // Check if username exists, append number if needed
            $baseUserName = $userName;
            $counter = 1;
            while (UmUser::where('user_name', $userName)->exists()) {
                $userName = $baseUserName.$counter;
                $counter++;
            }

            $user = UmUser::create([
                'first_name' => $validated['agent_name'],
                'last_name' => '',
                'user_name' => $userName,
                'user_password' => Hash::make($defaultPassword),
                'contact_no' => $validated['phone'],
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
                'base_salary' => $validated['base_salary'] ?? null,
                'commission_rate' => $validated['commission_rate'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'credit_period_days' => $validated['credit_period_days'] ?? null,
            ]);

            // Create bank accounts
            if (isset($validated['bank_accounts']) && is_array($validated['bank_accounts'])) {
                foreach ($validated['bank_accounts'] as $bankAccount) {
                    AdAgentHasBankAccount::create([
                        'agent_id' => $agent->id,
                        'bank_name' => $bankAccount['bank_name'],
                        'account_number' => $bankAccount['account_number'],
                        'branch' => $bankAccount['branch'] ?? null,
                        'is_primary' => $bankAccount['is_primary'] ?? false,
                    ]);
                }
            }

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
                'message' => 'Error creating agent: '.$e->getMessage(),
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
                    'base_salary' => $agent->base_salary,
                    'commission_rate' => $agent->commission_rate,
                    'credit_limit' => $agent->credit_limit,
                    'credit_period_days' => $agent->credit_period_days,
                    'bank_accounts' => $agent->bankAccounts->map(function ($bankAccount) {
                        return [
                            'bank_name' => $bankAccount->bank_name,
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
                'message' => 'Error loading agent: '.$e->getMessage(),
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
                'base_salary' => 'nullable|numeric|min:0',
                'commission_rate' => 'nullable|numeric|min:0|max:100',
                'credit_limit' => 'nullable|numeric|min:0',
                'credit_period_days' => 'nullable|integer|min:0',
                'bank_accounts' => 'required|array|min:1',
                'bank_accounts.*.bank_name' => 'required|string|max:255',
                'bank_accounts.*.account_number' => 'required|string|max:50',
                'bank_accounts.*.branch' => 'nullable|string|max:255',
                'bank_accounts.*.is_primary' => 'nullable|boolean',
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
                'base_salary' => $validated['base_salary'] ?? null,
                'commission_rate' => $validated['commission_rate'] ?? null,
                'credit_limit' => $validated['credit_limit'] ?? null,
                'credit_period_days' => $validated['credit_period_days'] ?? null,
            ]);

            // Delete existing bank accounts and create new ones
            if (isset($validated['bank_accounts']) && is_array($validated['bank_accounts'])) {
                // Delete all existing bank accounts for this agent
                $agent->bankAccounts()->delete();

                // Create new bank accounts
                foreach ($validated['bank_accounts'] as $bankAccount) {
                    AdAgentHasBankAccount::create([
                        'agent_id' => $agent->id,
                        'bank_name' => $bankAccount['bank_name'],
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
                'message' => 'Error updating agent: '.$e->getMessage(),
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
                'message' => 'Error deactivating agent: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Route Management - Display routes list
     */
    public function routeManageIndex()
    {
        $routes = AdRoute::with('agent')->get()->map(function ($route) {
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
            ];
        });

        // Get active agents for dropdown
        $agents = AdAgent::where('status', CommonVariables::$agentStatusActive)
            ->orderBy('agent_name')
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
                $join->on('cm_customer.id', '=', 'ad_route_has_customers.customer_id')
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
                if (! $d) {
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
                        'latitude' => $c->latitude ?? 6.9271,  // Real from database
                        'longitude' => $c->longitude ?? 79.8612, // Real from database
                    ],
                    'contact' => [
                        'contactPerson' => $d->contact_person_name ?? '',
                        'phoneNumber' => $c->phone ?? '',
                    ],
                    'assignedRouteId' => $d->route_id ?? null,
                    'stopSequence' => $d->stop_sequence ?? null,
                    'savedDistance' => $c->saved_distance,  // From ad_route_has_customers
                    'savedDuration' => $c->saved_duration,  // From ad_route_has_customers
                ];
            })
            ->filter() // Remove null entries
            ->values(); // Re-index array

        return view('agentDistribution.routeManagement', compact('routes', 'agents', 'customers'));
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
                $join->on('cm_customer.id', '=', 'ad_route_has_customers.customer_id')
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

                if (! $d) {
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

        return view('agentDistribution.Partials.visualRouteBuilder', compact('route', 'agents', 'customers'));
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
                'message' => 'Error creating route: '.$e->getMessage(),
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
                'message' => 'Error loading route: '.$e->getMessage(),
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
                'message' => 'Error updating route: '.$e->getMessage(),
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
                'message' => 'Error deactivating route: '.$e->getMessage(),
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
                'created_by' => auth()->id() ?? 1,
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

            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
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

            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $customer = CmCustomer::findOrFail($id);
            $customer->delete();

            return response()->json(['success' => true, 'message' => 'Customer deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()], 500);
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
            'customerCode' => 'CUS-'.$customerRecord->id, // Placeholder
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
                'message' => 'Error: '.$e->getMessage(),
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
                'message' => 'Error creating load: '.$e->getMessage(),
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
}
