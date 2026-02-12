<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OverheadManagementController extends Controller
{
    public function overheadManageIndex()
    {
        // Mock Data for Dashboard Stats
        $dashboardStats = [
            'totalOverhead' => 636000,
            'costPools' => 5,
            'activities' => 8,
            'allocations' => 12,
            'glIntegration' => [
                'mappedPools' => 3,
                'totalPools' => 5,
                'isMappingComplete' => false
            ]
        ];

        return view('overheadManagement.dashboard', compact('dashboardStats'));
    }

    public function expenseRecordingIndex()
    {
        // Mock Data for Expense Recording
        $overheadExpenses = [
            [
                'id' => 'oxp-1',
                'category' => 'utilities',
                'name' => 'Electricity Bill - January',
                'description' => 'Main facility power consumption',
                'amount' => 45000,
                'frequency' => 'monthly',
                'status' => 'paid',
                'date' => '2025-01-05',
                'vendor' => 'PowerCo',
                'invoiceNumber' => 'INV-2025-001',
                'isRecurring' => true,
                'costBehavior' => 'variable',
                'glJournalEntryId' => 'JE-2025-101', // Linked
                'createdAt' => '2025-01-05'
            ],
            [
                'id' => 'oxp-2',
                'category' => 'rent',
                'name' => 'Facility Rent - January',
                'description' => 'Monthly rent payment',
                'amount' => 120000,
                'frequency' => 'monthly',
                'status' => 'paid',
                'date' => '2025-01-01',
                'vendor' => 'City Properties',
                'invoiceNumber' => 'RENT-JAN-25',
                'isRecurring' => true,
                'costBehavior' => 'fixed',
                'glJournalEntryId' => 'JE-2025-102', // Linked
                'createdAt' => '2025-01-01'
            ],
            [
                'id' => 'oxp-3',
                'category' => 'maintenance',
                'name' => 'Emergency Mixer Repair',
                'description' => 'Replaced motor belt',
                'amount' => 8500,
                'frequency' => 'one-time',
                'status' => 'paid',
                'date' => '2025-01-12',
                'vendor' => 'FixIt Fast',
                'invoiceNumber' => 'SERV-992',
                'isRecurring' => false,
                'costBehavior' => 'variable',
                'glJournalEntryId' => null, // Missing JE
                'createdAt' => '2025-01-12'
            ],
            [
                'id' => 'oxp-4',
                'category' => 'marketing',
                'name' => 'Social Media Ads',
                'description' => 'Jan campaign',
                'amount' => 5000,
                'frequency' => 'monthly',
                'status' => 'pending',
                'date' => '2025-01-15',
                'vendor' => 'Facebook',
                'invoiceNumber' => 'FB-ADS-JAN',
                'isRecurring' => true,
                'costBehavior' => 'stepped',
                'glJournalEntryId' => null, // Missing JE
                'createdAt' => '2025-01-15'
            ],
            [
                'id' => 'oxp-5',
                'category' => 'administrative',
                'name' => 'Software Subscription',
                'description' => 'Accounting software',
                'amount' => 2500,
                'frequency' => 'monthly',
                'status' => 'scheduled',
                'date' => '2025-01-20',
                'vendor' => 'SaaS Corp',
                'invoiceNumber' => 'INV-999-22',
                'isRecurring' => true,
                'costBehavior' => 'fixed',
                'glJournalEntryId' => 'JE-2025-105', // Linked
                'createdAt' => '2025-01-01'
            ]
        ];

        return view('overheadManagement.expenseRecording', compact('overheadExpenses'));
    }

    public function costPoolsIndex()
    {
        // Mock Data for Cost Pools
        $costPools = [
            [
                'id' => 'pool-1',
                'name' => 'Facility Costs',
                'description' => 'Rent, utilities, and maintenance for the entire facility',
                'category' => 'rent', // utilities, rent, salaries, equipment, etc.
                'costBehavior' => 'fixed', // fixed, variable, semi-variable, stepped
                'allocationMethod' => 'by-driver', // by-driver, equal, by-usage, by-revenue, abc, manual
                'driverId' => 'driver-4', // floor-space
                'totalAmount' => 150000,
                'expenseIds' => ['exp-1', 'exp-2'],
                'isActive' => true,
                'createdAt' => '2025-01-01',
                'updatedAt' => '2025-01-15'
            ],
            [
                'id' => 'pool-2',
                'name' => 'Machinery Operations',
                'description' => 'Power, repairs, and depreciation for production machines',
                'category' => 'maintenance',
                'costBehavior' => 'variable',
                'allocationMethod' => 'by-driver',
                'driverId' => 'driver-3', // machine-hours
                'totalAmount' => 85000,
                'expenseIds' => ['exp-3', 'exp-4'],
                'isActive' => true,
                'createdAt' => '2025-01-02',
                'updatedAt' => '2025-01-18'
            ],
            [
                'id' => 'pool-3',
                'name' => 'Quality Control',
                'description' => 'Staff salaries and testing supplies',
                'category' => 'salaries',
                'costBehavior' => 'stepped',
                'allocationMethod' => 'by-driver',
                'driverId' => 'driver-2', // labor-hours
                'totalAmount' => 60000,
                'expenseIds' => ['exp-5'],
                'isActive' => true,
                'createdAt' => '2025-01-05',
                'updatedAt' => '2025-01-20'
            ],
            [
                'id' => 'pool-4',
                'name' => 'Administrative Overhead',
                'description' => 'Office supplies, software, and admin salaries',
                'category' => 'administrative',
                'costBehavior' => 'fixed',
                'allocationMethod' => 'equal', // Allocates equally across sections
                'driverId' => null,
                'totalAmount' => 45000,
                'expenseIds' => ['exp-6', 'exp-7'],
                'isActive' => true,
                'createdAt' => '2025-01-10',
                'updatedAt' => '2025-01-22'
            ]
        ];

        $expenses = [
            [
                'id' => 'exp-1',
                'name' => 'Monthly Facility Rent',
                'description' => 'Base rent for January',
                'amount' => 120000,
                'category' => 'rent',
                'vendor' => 'City Properties Ltd',
                'frequency' => 'monthly',
                'status' => 'paid',
                'date' => '2025-01-01',
                'paidDate' => '2025-01-05',
                'isRecurring' => true,
                'costPoolId' => 'pool-1',
                'costBehavior' => 'fixed',
                'tags' => ['rent', 'facility'],
                'createdBy' => 'System'
            ],
            [
                'id' => 'exp-2',
                'name' => 'Electricity Bill',
                'description' => 'Facility-wide power usage',
                'amount' => 30000,
                'category' => 'utilities',
                'vendor' => 'PowerCo',
                'frequency' => 'monthly',
                'status' => 'paid',
                'date' => '2025-01-28',
                'paidDate' => '2025-01-30',
                'isRecurring' => true,
                'costPoolId' => 'pool-1',
                'costBehavior' => 'variable',
                'tags' => ['utilities'],
                'createdBy' => 'Admin'
            ],
            [
                'id' => 'exp-3',
                'name' => 'Machine Parts',
                'description' => 'Replacement parts for mixer',
                'amount' => 15000,
                'category' => 'maintenance',
                'vendor' => 'Parts R Us',
                'frequency' => 'one-time',
                'status' => 'paid',
                'date' => '2025-01-15',
                'paidDate' => '2025-01-16',
                'isRecurring' => false,
                'costPoolId' => 'pool-2',
                'costBehavior' => 'variable',
                'tags' => ['repair'],
                'createdBy' => 'Maintenance Mgr'
            ],
            [
                'id' => 'exp-4',
                'name' => 'Machine Depreciation',
                'description' => 'Monthly depreciation allocate',
                'amount' => 70000,
                'category' => 'equipment',
                'vendor' => 'Internal',
                'frequency' => 'monthly',
                'status' => 'paid', // Accounting entry
                'date' => '2025-01-31',
                'paidDate' => '2025-01-31',
                'isRecurring' => true,
                'costPoolId' => 'pool-2',
                'costBehavior' => 'fixed',
                'tags' => ['depreciation'],
                'createdBy' => 'System'
            ],
            [
                'id' => 'exp-5',
                'name' => 'QC Staff Salaries',
                'description' => 'Payroll for QC team',
                'amount' => 60000,
                'category' => 'salaries',
                'vendor' => 'Payroll',
                'frequency' => 'monthly',
                'status' => 'paid',
                'date' => '2025-01-25',
                'paidDate' => '2025-01-28',
                'isRecurring' => true,
                'costPoolId' => 'pool-3',
                'costBehavior' => 'stepped',
                'tags' => ['payroll'],
                'createdBy' => 'HR'
            ],
            [
                'id' => 'exp-6',
                'name' => 'Office Supplies',
                'description' => 'Paper, ink, pens',
                'amount' => 5000,
                'category' => 'administrative',
                'vendor' => 'OfficeDepot',
                'frequency' => 'one-time',
                'status' => 'paid',
                'date' => '2025-01-10',
                'paidDate' => '2025-01-12',
                'isRecurring' => false,
                'costPoolId' => 'pool-4',
                'costBehavior' => 'variable',
                'tags' => ['supplies'],
                'createdBy' => 'Admin'
            ],
            [
                'id' => 'exp-7',
                'name' => 'Admin Salaries',
                'description' => 'Admin staff payroll',
                'amount' => 40000,
                'category' => 'administrative',
                'vendor' => 'Payroll',
                'frequency' => 'monthly',
                'status' => 'paid',
                'date' => '2025-01-25',
                'paidDate' => '2025-01-28',
                'isRecurring' => true,
                'costPoolId' => 'pool-4',
                'costBehavior' => 'fixed',
                'tags' => ['payroll'],
                'createdBy' => 'HR'
            ]
        ];

        $costDrivers = [
            [
                'id' => 'driver-1',
                'name' => 'Units Produced',
                'unit' => 'Units',
                'total' => 5000,
                'isActive' => true
            ],
            [
                'id' => 'driver-2',
                'name' => 'Labor Hours',
                'unit' => 'Hours',
                'total' => 1200,
                'isActive' => true
            ],
            [
                'id' => 'driver-3',
                'name' => 'Machine Hours',
                'unit' => 'Hours',
                'total' => 800,
                'isActive' => true
            ],
            [
                'id' => 'driver-4',
                'name' => 'Floor Space',
                'unit' => 'Sq Ft',
                'total' => 10000,
                'isActive' => true
            ]
        ];

        return view('overheadManagement.costPools', compact('costPools', 'expenses', 'costDrivers'));
    }

    public function activityBasedCostingIndex()
    {
        // Mock Data for ABC
        $activities = [
            [
                'id' => 'act-1',
                'name' => 'Machine Setup',
                'description' => 'Preparing machines for production runs',
                'category' => 'batch-level',
                'primaryDriverId' => 'driver-1',
                'estimatedCost' => 25000,
                'actualCost' => 26500,
                'costPoolIds' => ['pool-3'],
                'isActive' => true
            ],
            [
                'id' => 'act-2',
                'name' => 'Quality Inspection',
                'description' => 'Inspecting finished goods',
                'category' => 'unit-level',
                'primaryDriverId' => 'driver-2',
                'estimatedCost' => 15000,
                'actualCost' => 14200,
                'costPoolIds' => ['pool-4'],
                'isActive' => true
            ],
            [
                'id' => 'act-3',
                'name' => 'Material Handling',
                'description' => 'Moving raw materials to production floor',
                'category' => 'batch-level',
                'primaryDriverId' => 'driver-1',
                'estimatedCost' => 18000,
                'actualCost' => 19500,
                'costPoolIds' => ['pool-4'],
                'isActive' => true
            ],
            [
                'id' => 'act-4',
                'name' => 'Facility Maintenance',
                'description' => 'General building upkeep',
                'category' => 'facility-level',
                'primaryDriverId' => 'driver-3',
                'estimatedCost' => 45000,
                'actualCost' => 45000,
                'costPoolIds' => ['pool-1', 'pool-2'],
                'isActive' => true
            ]
        ];

        $costDrivers = [
            [
                'id' => 'driver-1',
                'name' => 'Setup Hours',
                'description' => 'Hours spent on machine setup',
                'type' => 'time-based',
                'unit' => 'hours',
                'total' => 450,
                'values' => ['kitchen' => 150, 'cake' => 200, 'bakery' => 100],
                'isActive' => true
            ],
            [
                'id' => 'driver-2',
                'name' => 'Units Inspected',
                'description' => 'Number of items inspected',
                'type' => 'volume-based',
                'unit' => 'units',
                'total' => 5000,
                'values' => ['kitchen' => 2000, 'cake' => 1500, 'bakery' => 1500],
                'isActive' => true
            ],
            [
                'id' => 'driver-3',
                'name' => 'Square Footage',
                'description' => 'Floor space occupied',
                'type' => 'facility-based',
                'unit' => 'sq ft',
                'total' => 10000,
                'values' => ['kitchen' => 4000, 'cake' => 3000, 'bakery' => 3000],
                'isActive' => true
            ]
        ];

        $costPools = [
            ['id' => 'pool-1', 'name' => 'Utilities', 'totalAmount' => 45000],
            ['id' => 'pool-2', 'name' => 'Rent', 'totalAmount' => 120000],
            ['id' => 'pool-3', 'name' => 'Machine Maintenance', 'totalAmount' => 35000],
            ['id' => 'pool-4', 'name' => 'Indirect Labor', 'totalAmount' => 85000],
        ];

        return view('overheadManagement.activityBasedCosting', compact('activities', 'costDrivers', 'costPools'));
    }

    public function allocationWizardIndex()
    {
        // Mock Data for Wizard
        $costPools = [
            ['id' => 'pool-1', 'name' => 'Utilities', 'description' => 'Electricity and Water', 'totalAmount' => 45000, 'expenseIds' => ['exp-1', 'exp-2'], 'driverId' => 'driver-1', 'category' => 'facility'],
            ['id' => 'pool-2', 'name' => 'Rent', 'description' => 'Factory Rent', 'totalAmount' => 120000, 'expenseIds' => ['exp-3'], 'driverId' => 'driver-2', 'category' => 'facility'],
            ['id' => 'pool-3', 'name' => 'Maintenance', 'description' => 'Machine Maintenance', 'totalAmount' => 35000, 'expenseIds' => ['exp-4'], 'driverId' => 'driver-3', 'category' => 'production'],
            ['id' => 'pool-4', 'name' => 'Indirect Labor', 'description' => 'Supervisors & Cleaning', 'totalAmount' => 85000, 'expenseIds' => ['exp-5', 'exp-6'], 'driverId' => 'driver-4', 'category' => 'labor'],
        ];

        $activities = [
            ['id' => 'act-1', 'name' => 'Machine Operation', 'description' => 'Running production machines', 'isActive' => true, 'primaryDriverId' => 'driver-3', 'category' => 'production'],
            ['id' => 'act-2', 'name' => 'Facility Usage', 'description' => 'Occupying space', 'isActive' => true, 'primaryDriverId' => 'driver-2', 'category' => 'facility'],
            ['id' => 'act-3', 'name' => 'Energy Consumption', 'description' => 'Power usage', 'isActive' => true, 'primaryDriverId' => 'driver-1', 'category' => 'utilities'],
            ['id' => 'act-4', 'name' => 'Supervision', 'description' => 'Managing staff', 'isActive' => true, 'primaryDriverId' => 'driver-4', 'category' => 'labor'],
        ];

        $costDrivers = [
            ['id' => 'driver-1', 'name' => 'Kilowatt Hours', 'description' => 'KWh Consumed', 'type' => 'resource-usage', 'unit' => 'KWh', 'isActive' => true, 'total' => 5000, 'values' => ['kitchen' => 2000, 'cake' => 1500, 'bakery' => 1500]],
            ['id' => 'driver-2', 'name' => 'Square Feet', 'description' => 'Floor Space', 'type' => 'facility-usage', 'unit' => 'Sq Ft', 'isActive' => true, 'total' => 10000, 'values' => ['kitchen' => 4000, 'cake' => 3000, 'bakery' => 3000]],
            ['id' => 'driver-3', 'name' => 'Machine Hours', 'description' => 'Hours of operation', 'type' => 'activity-based', 'unit' => 'Hrs', 'isActive' => true, 'total' => 800, 'values' => ['kitchen' => 300, 'cake' => 200, 'bakery' => 300]],
            ['id' => 'driver-4', 'name' => 'Labor Hours', 'description' => 'Direct Labor Hours', 'type' => 'labor-based', 'unit' => 'Hrs', 'isActive' => true, 'total' => 1200, 'values' => ['kitchen' => 500, 'cake' => 300, 'bakery' => 400]],
        ];

        $expenses = [
            ['id' => 'exp-1', 'name' => 'Electricity Bill - June', 'vendor' => 'Power Co', 'amount' => 35000, 'status' => 'paid', 'costPoolId' => 'pool-1'],
            ['id' => 'exp-2', 'name' => 'Water Bill - June', 'vendor' => 'Water Works', 'amount' => 10000, 'status' => 'paid', 'costPoolId' => 'pool-1'],
            ['id' => 'exp-3', 'name' => 'Monthly Rent', 'vendor' => 'Landlord Inc', 'amount' => 120000, 'status' => 'paid', 'costPoolId' => 'pool-2'],
            ['id' => 'exp-4', 'name' => 'Machine Servicing', 'vendor' => 'FixIt Pros', 'amount' => 35000, 'status' => 'pending', 'costPoolId' => 'pool-3'],
            ['id' => 'exp-5', 'name' => 'Supervisor Salary', 'vendor' => 'Payroll', 'amount' => 60000, 'status' => 'paid', 'costPoolId' => 'pool-4'],
            ['id' => 'exp-6', 'name' => 'Cleaning Services', 'vendor' => 'CleanTeam', 'amount' => 25000, 'status' => 'paid', 'costPoolId' => 'pool-4'],
        ];

        return view('overheadManagement.allocationWizard', compact('costPools', 'activities', 'costDrivers', 'expenses'));
    }

    public function allocationPostingIndex()
    {
        $allocations = [
            [
                'id' => 'alloc-001',
                'name' => 'June 2024 Final Allocation',
                'status' => 'posted',
                'period' => '2024-06',
                'totalAllocated' => 150000.00,
                'createdAt' => '2024-06-30T10:00:00',
                'postedAt' => '2024-07-01T09:30:00',
                'postedBy' => 'Admin',
                'glJournalEntryId' => 'JE-2024-001',
                'selectedPools' => 5,
                'allocations' => [
                    'kitchen' => ['amount' => 60000, 'percentage' => 40.0],
                    'cake' => ['amount' => 40000, 'percentage' => 26.7],
                    'bakery' => ['amount' => 50000, 'percentage' => 33.3],
                ]
            ],
            [
                'id' => 'alloc-002',
                'name' => 'July 2024 Partial Allocation',
                'status' => 'posted',
                'period' => '2024-07',
                'totalAllocated' => 75000.00,
                'createdAt' => '2024-07-15T14:20:00',
                'postedAt' => '2024-07-15T15:00:00',
                'postedBy' => 'Manager',
                'glJournalEntryId' => null, // posted but no JE yet
                'selectedPools' => 2,
                'allocations' => [
                    'kitchen' => ['amount' => 30000, 'percentage' => 40.0],
                    'cake' => ['amount' => 20000, 'percentage' => 26.7],
                    'bakery' => ['amount' => 25000, 'percentage' => 33.3],
                ]
            ],
            [
                'id' => 'alloc-003',
                'name' => 'August 2024 Draft Allocation',
                'status' => 'draft',
                'period' => '2024-08',
                'totalAllocated' => 160000.00,
                'createdAt' => '2024-08-31T11:00:00',
                'postedAt' => null,
                'postedBy' => null,
                'glJournalEntryId' => null,
                'selectedPools' => 6,
                'allocations' => [
                    'kitchen' => ['amount' => 64000, 'percentage' => 40.0],
                    'cake' => ['amount' => 42666, 'percentage' => 26.6],
                    'bakery' => ['amount' => 53334, 'percentage' => 33.4],
                ]
            ],
            [
                'id' => 'alloc-004',
                'name' => 'May 2024 Revised Allocation',
                'status' => 'reversed',
                'period' => '2024-05',
                'totalAllocated' => 145000.00,
                'createdAt' => '2024-05-31T09:00:00',
                'postedAt' => '2024-06-01T08:00:00',
                'postedBy' => 'Admin',
                'glJournalEntryId' => 'JE-2024-000-REV',
                'selectedPools' => 5,
                'allocations' => [
                    'kitchen' => ['amount' => 58000, 'percentage' => 40.0],
                    'cake' => ['amount' => 38000, 'percentage' => 26.2],
                    'bakery' => ['amount' => 49000, 'percentage' => 33.8],
                ]
            ],
            [
                'id' => 'alloc-005',
                'name' => 'September 2024 Simulation',
                'status' => 'simulated',
                'period' => '2024-09',
                'totalAllocated' => 155000.00,
                'createdAt' => '2024-09-01T10:15:00',
                'postedAt' => null,
                'postedBy' => null,
                'glJournalEntryId' => null,
                'selectedPools' => 5,
                'allocations' => [
                    'kitchen' => ['amount' => 62000, 'percentage' => 40.0],
                    'cake' => ['amount' => 41000, 'percentage' => 26.5],
                    'bakery' => ['amount' => 52000, 'percentage' => 33.5],
                ]
            ],
        ];

        return view('overheadManagement.allocationPosting', compact('allocations'));
    }

    public function varianceReconciliationIndex()
    {
        // Mock Data for Variances
        $variances = [
            [
                'period' => '2024-06',
                'actualOverhead' => 53000,
                'appliedOverhead' => 52000,
                'variance' => 1000,
                'varianceType' => 'under-applied',
                'variancePercent' => 1.9,
                'expenseCount' => 45,
                'allocationCount' => 1,
                'hasReconciliationEntry' => false,
                'reconciliationJEId' => null
            ],
            [
                'period' => '2024-05',
                'actualOverhead' => 48000,
                'appliedOverhead' => 48500,
                'variance' => -500,
                'varianceType' => 'over-applied',
                'variancePercent' => 1.0,
                'expenseCount' => 42,
                'allocationCount' => 1,
                'hasReconciliationEntry' => true,
                'reconciliationJEId' => 'JE-2024-005'
            ],
            [
                'period' => '2024-04',
                'actualOverhead' => 45000,
                'appliedOverhead' => 45000,
                'variance' => 0,
                'varianceType' => 'balanced',
                'variancePercent' => 0.0,
                'expenseCount' => 40,
                'allocationCount' => 1,
                'hasReconciliationEntry' => false,
                'reconciliationJEId' => null
            ],
            [
                'period' => '2024-03',
                'actualOverhead' => 51000,
                'appliedOverhead' => 49000,
                'variance' => 2000,
                'varianceType' => 'under-applied',
                'variancePercent' => 3.9,
                'expenseCount' => 48,
                'allocationCount' => 1,
                'hasReconciliationEntry' => true,
                'reconciliationJEId' => 'JE-2024-003'
            ],
        ];

        $currentPeriodVariance = $variances[0];

        return view('overheadManagement.varianceReconciliation', compact('variances', 'currentPeriodVariance'));
    }

    public function allocationHistoryIndex()
    {
        // Mock Data for Allocations
        $executions = [
            [
                'id' => 'exec-001',
                'name' => 'June 2024 Overhead Allocation',
                'period' => 'June 2024',
                'status' => 'posted',
                'createdAt' => '2024-06-30 14:30',
                'createdBy' => 'Admin User',
                'postedAt' => '2024-07-01 09:00',
                'totalAllocated' => 52000,
                'notes' => 'Standard monthly allocation. Adjustments made for higher utility costs.',
                'selectedPools' => ['Utilities', 'Rent', 'Depreciation', 'Maintenance', 'QC'],
                'expensesByPool' => [
                    ['poolId' => 'p1', 'poolName' => 'Utilities', 'amount' => 15000],
                    ['poolId' => 'p2', 'poolName' => 'Rent & Depreciation', 'amount' => 12000],
                    ['poolId' => 'p3', 'poolName' => 'Quality Control', 'amount' => 8500],
                ],
                'calculatedRates' => [
                    ['poolId' => 'p1', 'poolName' => 'Utilities', 'driverName' => 'Machine Hours', 'totalCost' => 15000, 'totalVolume' => 1000, 'ratePerUnit' => 15.00],
                    ['poolId' => 'p2', 'poolName' => 'Rent & Depreciation', 'driverName' => 'Direct Labor Hours', 'totalCost' => 12000, 'totalVolume' => 2000, 'ratePerUnit' => 6.00],
                ],
                'allocations' => [
                    'kitchen' => ['amount' => 18000, 'percentage' => 34.6],
                    'cake' => ['amount' => 20000, 'percentage' => 38.5],
                    'bakery' => ['amount' => 14000, 'percentage' => 26.9],
                ]
            ],
            [
                'id' => 'exec-002',
                'name' => 'May 2024 Overhead Allocation',
                'period' => 'May 2024',
                'status' => 'posted',
                'createdAt' => '2024-05-31 16:00',
                'createdBy' => 'Admin User',
                'postedAt' => '2024-06-01 08:30',
                'totalAllocated' => 48500,
                'notes' => 'Routine allocation.',
                'selectedPools' => ['Utilities', 'Rent', 'Depreciation'],
                'expensesByPool' => [
                    ['poolId' => 'p1', 'poolName' => 'Utilities', 'amount' => 14000],
                    ['poolId' => 'p2', 'poolName' => 'Rent', 'amount' => 11000],
                ],
                'calculatedRates' => [],
                'allocations' => [
                    'kitchen' => ['amount' => 16000, 'percentage' => 33.0],
                    'cake' => ['amount' => 19000, 'percentage' => 39.2],
                    'bakery' => ['amount' => 13500, 'percentage' => 27.8],
                ]
            ],
            [
                'id' => 'exec-003',
                'name' => 'July 2024 Preliminary',
                'period' => 'July 2024',
                'status' => 'draft',
                'createdAt' => '2024-07-25 10:15',
                'createdBy' => 'John Doe',
                'postedAt' => null,
                'totalAllocated' => 0,
                'notes' => 'Initial draft for July estimation.',
                'selectedPools' => ['Utilities'],
                'expensesByPool' => [],
                'calculatedRates' => [],
                'allocations' => [
                    'kitchen' => ['amount' => 0, 'percentage' => 0],
                    'cake' => ['amount' => 0, 'percentage' => 0],
                    'bakery' => ['amount' => 0, 'percentage' => 0],
                ]
            ],
            [
                'id' => 'exec-004',
                'name' => 'April 2024 Adjustment',
                'period' => 'April 2024',
                'status' => 'reversed',
                'createdAt' => '2024-04-30 11:00',
                'createdBy' => 'Jane Smith',
                'postedAt' => '2024-05-01 09:00',
                'reversedAt' => '2024-05-02 14:00',
                'reversedBy' => 'Admin User',
                'totalAllocated' => 45000,
                'notes' => 'Mistake in driver data, reversed.',
                'selectedPools' => ['Maintenance'],
                'expensesByPool' => [],
                'calculatedRates' => [],
                'allocations' => [
                    'kitchen' => ['amount' => 15000, 'percentage' => 33.3],
                    'cake' => ['amount' => 15000, 'percentage' => 33.3],
                    'bakery' => ['amount' => 15000, 'percentage' => 33.3],
                ]
            ],
        ];

        // Calculate Stats
        $stats = [
            'total' => count($executions),
            'draft' => collect($executions)->where('status', 'draft')->count(),
            'posted' => collect($executions)->where('status', 'posted')->count(),
            'reversed' => collect($executions)->where('status', 'reversed')->count(),
            'totalAllocated' => collect($executions)->where('status', 'posted')->sum('totalAllocated'),
        ];

        return view('overheadManagement.allocationHistory', compact('executions', 'stats'));
    }

    public function analyticsDashboardIndex()
    {
        // Mock Data for Monthly Trend
        $monthlyTrendData = [
            ['month' => 'Jan', 'overhead' => 45000, 'allocated' => 42000, 'variance' => 3000],
            ['month' => 'Feb', 'overhead' => 48000, 'allocated' => 46500, 'variance' => 1500],
            ['month' => 'Mar', 'overhead' => 52000, 'allocated' => 50000, 'variance' => 2000],
            ['month' => 'Apr', 'overhead' => 49000, 'allocated' => 48000, 'variance' => 1000],
            ['month' => 'May', 'overhead' => 51000, 'allocated' => 49500, 'variance' => 1500],
            ['month' => 'Jun', 'overhead' => 53000, 'allocated' => 52000, 'variance' => 1000],
        ];

        // Mock Data for Cost Pool Distribution
        $costPoolDistribution = [
            ['name' => 'Utilities', 'value' => 15000, 'percentage' => 28.3, 'color' => '#D4A017'],
            ['name' => 'Rent & Depreciation', 'value' => 12000, 'percentage' => 22.6, 'color' => '#F4C430'],
            ['name' => 'Quality Control', 'value' => 8500, 'percentage' => 16.0, 'color' => '#FFD700'],
            ['name' => 'Material Handling', 'value' => 9000, 'percentage' => 17.0, 'color' => '#DAA520'],
            ['name' => 'Maintenance', 'value' => 8500, 'percentage' => 16.0, 'color' => '#B8860B'],
        ];

        // Mock Data for Activity Cost
        $activityCostData = [
            ['activity' => 'Machine Hours', 'cost' => 18500, 'driver' => 1250, 'rate' => 14.8],
            ['activity' => 'Direct Labor Hours', 'cost' => 15000, 'driver' => 1800, 'rate' => 8.33],
            ['activity' => 'Setup Hours', 'cost' => 8500, 'driver' => 240, 'rate' => 35.42],
            ['activity' => 'QC Inspections', 'cost' => 6000, 'driver' => 450, 'rate' => 13.33],
            ['activity' => 'Material Moves', 'cost' => 5000, 'driver' => 380, 'rate' => 13.16],
        ];

        // Mock Data for Product Allocation
        $productAllocationData = [
            ['product' => 'Baguette', 'materials' => 2500, 'labor' => 3200, 'overhead' => 4800, 'total' => 10500, 'margin' => 25],
            ['product' => 'Croissant', 'materials' => 3200, 'labor' => 4500, 'overhead' => 6200, 'total' => 13900, 'margin' => 32],
            ['product' => 'Sourdough', 'materials' => 2800, 'labor' => 3800, 'overhead' => 5100, 'total' => 11700, 'margin' => 28],
            ['product' => 'Danish', 'materials' => 3500, 'labor' => 4200, 'overhead' => 6800, 'total' => 14500, 'margin' => 35],
            ['product' => 'Multigrain', 'materials' => 2900, 'labor' => 3500, 'overhead' => 4900, 'total' => 11300, 'margin' => 26],
        ];

        // Mock Data for Allocation Rate Trend
        $allocationRateTrend = [
            ['month' => 'Jan', 'machineHours' => 14.2, 'laborHours' => 8.1, 'setupHours' => 34.5],
            ['month' => 'Feb', 'machineHours' => 14.5, 'laborHours' => 8.2, 'setupHours' => 35.0],
            ['month' => 'Mar', 'machineHours' => 14.8, 'laborHours' => 8.4, 'setupHours' => 35.4],
            ['month' => 'Apr', 'machineHours' => 14.6, 'laborHours' => 8.3, 'setupHours' => 35.2],
            ['month' => 'May', 'machineHours' => 14.9, 'laborHours' => 8.5, 'setupHours' => 35.8],
            ['month' => 'Jun', 'machineHours' => 15.0, 'laborHours' => 8.6, 'setupHours' => 36.0],
        ];

        return view('overheadManagement.analysisDashboard', compact('monthlyTrendData', 'costPoolDistribution', 'activityCostData', 'productAllocationData', 'allocationRateTrend'));
    }

    public function costAllocationReportIndex()
    {
        // Mock Data for Allocation Summary
        $allocationSummary = [
            'period' => 'June 2024',
            'totalOverhead' => 53000,
            'allocatedOverhead' => 52000,
            'unallocatedOverhead' => 1000,
            'allocationRate' => 98.1,
            'costPools' => 5,
            'products' => 8,
            'activities' => 5,
        ];

        // Mock Data for Cost Pool Allocations
        $costPoolAllocations = [
            [
                'id' => 'cp-1',
                'name' => 'Utilities',
                'total' => 15000,
                'allocated' => 14800,
                'unallocated' => 200,
                'products' => [
                    ['name' => 'Baguette', 'amount' => 2800, 'percentage' => 18.9],
                    ['name' => 'Croissant', 'amount' => 3500, 'percentage' => 23.6],
                    ['name' => 'Sourdough', 'amount' => 2900, 'percentage' => 19.6],
                    ['name' => 'Danish', 'amount' => 3200, 'percentage' => 21.6],
                    ['name' => 'Multigrain', 'amount' => 2400, 'percentage' => 16.2],
                ]
            ],
            [
                'id' => 'cp-2',
                'name' => 'Rent & Depreciation',
                'total' => 12000,
                'allocated' => 12000,
                'unallocated' => 0,
                'products' => [
                    ['name' => 'Baguette', 'amount' => 2200, 'percentage' => 18.3],
                    ['name' => 'Croissant', 'amount' => 2800, 'percentage' => 23.3],
                    ['name' => 'Sourdough', 'amount' => 2400, 'percentage' => 20.0],
                    ['name' => 'Danish', 'amount' => 2600, 'percentage' => 21.7],
                    ['name' => 'Multigrain', 'amount' => 2000, 'percentage' => 16.7],
                ]
            ],
            [
                'id' => 'cp-3',
                'name' => 'Quality Control',
                'total' => 8500,
                'allocated' => 8300,
                'unallocated' => 200,
                'products' => [
                    ['name' => 'Baguette', 'amount' => 1500, 'percentage' => 18.1],
                    ['name' => 'Croissant', 'amount' => 2000, 'percentage' => 24.1],
                    ['name' => 'Sourdough', 'amount' => 1600, 'percentage' => 19.3],
                    ['name' => 'Danish', 'amount' => 1800, 'percentage' => 21.7],
                    ['name' => 'Multigrain', 'amount' => 1400, 'percentage' => 16.9],
                ]
            ],
            [
                'id' => 'cp-4',
                'name' => 'Material Handling',
                'total' => 9000,
                'allocated' => 8900,
                'unallocated' => 100,
                'products' => [
                    ['name' => 'Baguette', 'amount' => 1700, 'percentage' => 19.1],
                    ['name' => 'Croissant', 'amount' => 2100, 'percentage' => 23.6],
                    ['name' => 'Sourdough', 'amount' => 1800, 'percentage' => 20.2],
                    ['name' => 'Danish', 'amount' => 1900, 'percentage' => 21.3],
                    ['name' => 'Multigrain', 'amount' => 1400, 'percentage' => 15.7],
                ]
            ],
            [
                'id' => 'cp-5',
                'name' => 'Maintenance',
                'total' => 8500,
                'allocated' => 8000,
                'unallocated' => 500,
                'products' => [
                    ['name' => 'Baguette', 'amount' => 1500, 'percentage' => 18.8],
                    ['name' => 'Croissant', 'amount' => 1900, 'percentage' => 23.8],
                    ['name' => 'Sourdough', 'amount' => 1600, 'percentage' => 20.0],
                    ['name' => 'Danish', 'amount' => 1700, 'percentage' => 21.3],
                    ['name' => 'Multigrain', 'amount' => 1300, 'percentage' => 16.3],
                ]
            ],
        ];

        // Mock Data for Product Allocation Detail
        $productAllocationDetail = [
            [
                'product' => 'Baguette',
                'totalOverhead' => 9700,
                'allocations' => [
                    ['costPool' => 'Utilities', 'amount' => 2800, 'activity' => 'Machine Hours', 'driver' => 180],
                    ['costPool' => 'Rent & Depreciation', 'amount' => 2200, 'activity' => 'Direct Labor Hours', 'driver' => 250],
                    ['costPool' => 'Quality Control', 'amount' => 1500, 'activity' => 'QC Inspections', 'driver' => 110],
                    ['costPool' => 'Material Handling', 'amount' => 1700, 'activity' => 'Material Moves', 'driver' => 95],
                    ['costPool' => 'Maintenance', 'amount' => 1500, 'activity' => 'Machine Hours', 'driver' => 85],
                ]
            ],
            [
                'product' => 'Croissant',
                'totalOverhead' => 12300,
                'allocations' => [
                    ['costPool' => 'Utilities', 'amount' => 3500, 'activity' => 'Machine Hours', 'driver' => 230],
                    ['costPool' => 'Rent & Depreciation', 'amount' => 2800, 'activity' => 'Direct Labor Hours', 'driver' => 320],
                    ['costPool' => 'Quality Control', 'amount' => 2000, 'activity' => 'QC Inspections', 'driver' => 145],
                    ['costPool' => 'Material Handling', 'amount' => 2100, 'activity' => 'Material Moves', 'driver' => 115],
                    ['costPool' => 'Maintenance', 'amount' => 1900, 'activity' => 'Machine Hours', 'driver' => 105],
                ]
            ],
            [
                'product' => 'Sourdough',
                'totalOverhead' => 10300,
                'allocations' => [
                    ['costPool' => 'Utilities', 'amount' => 2900, 'activity' => 'Machine Hours', 'driver' => 195],
                    ['costPool' => 'Rent & Depreciation', 'amount' => 2400, 'activity' => 'Direct Labor Hours', 'driver' => 275],
                    ['costPool' => 'Quality Control', 'amount' => 1600, 'activity' => 'QC Inspections', 'driver' => 120],
                    ['costPool' => 'Material Handling', 'amount' => 1800, 'activity' => 'Material Moves', 'driver' => 100],
                    ['costPool' => 'Maintenance', 'amount' => 1600, 'activity' => 'Machine Hours', 'driver' => 90],
                ]
            ],
        ];

        // Mock Data for Activity Driver Report
        $activityDriverReport = [
            [
                'activity' => 'Machine Hours',
                'totalHours' => 1250,
                'costPoolAllocated' => 23500,
                'rate' => 18.8,
                'products' => [
                    ['name' => 'Baguette', 'hours' => 180, 'cost' => 3384],
                    ['name' => 'Croissant', 'hours' => 230, 'cost' => 4324],
                    ['name' => 'Sourdough', 'hours' => 195, 'cost' => 3666],
                    ['name' => 'Danish', 'hours' => 210, 'cost' => 3948],
                    ['name' => 'Multigrain', 'hours' => 165, 'cost' => 3102],
                ]
            ],
            [
                'activity' => 'Direct Labor Hours',
                'totalHours' => 1800,
                'costPoolAllocated' => 12000,
                'rate' => 6.67,
                'products' => [
                    ['name' => 'Baguette', 'hours' => 250, 'cost' => 1668],
                    ['name' => 'Croissant', 'hours' => 320, 'cost' => 2134],
                    ['name' => 'Sourdough', 'hours' => 275, 'cost' => 1834],
                    ['name' => 'Danish', 'hours' => 290, 'cost' => 1934],
                    ['name' => 'Multigrain', 'hours' => 240, 'cost' => 1600],
                ]
            ],
            [
                'activity' => 'QC Inspections',
                'totalInspections' => 450,
                'costPoolAllocated' => 8500,
                'rate' => 18.89,
                'products' => [
                    ['name' => 'Baguette', 'hours' => 110, 'cost' => 2078],
                    ['name' => 'Croissant', 'hours' => 145, 'cost' => 2739],
                    ['name' => 'Sourdough', 'hours' => 120, 'cost' => 2267],
                    ['name' => 'Danish', 'hours' => 130, 'cost' => 2456],
                    ['name' => 'Multigrain', 'hours' => 95, 'cost' => 1795],
                ]
            ],
        ];

        return view('overheadManagement.costAllocationReport', compact('allocationSummary', 'costPoolAllocations', 'productAllocationDetail', 'activityDriverReport'));
    }

    public function productCostingAnalysisIndex()
    {
        // Mock Data for Product Costs
        $productCostData = [
            [
                'id' => 'p-1',
                'name' => 'Baguette',
                'sku' => 'BAG-001',
                'category' => 'Bread',
                'materials' => 2500,
                'labor' => 3200,
                'overhead' => 4800,
                'totalCost' => 10500,
                'sellingPrice' => 14000,
                'margin' => 3500,
                'marginPercent' => 25.0,
                'status' => 'profitable',
                'trend' => 'up'
            ],
            [
                'id' => 'p-2',
                'name' => 'Croissant',
                'sku' => 'CRO-001',
                'category' => 'Pastry',
                'materials' => 3200,
                'labor' => 4500,
                'overhead' => 6200,
                'totalCost' => 13900,
                'sellingPrice' => 20500,
                'margin' => 6600,
                'marginPercent' => 32.2,
                'status' => 'profitable',
                'trend' => 'up'
            ],
            [
                'id' => 'p-3',
                'name' => 'Sourdough',
                'sku' => 'SOU-001',
                'category' => 'Bread',
                'materials' => 2800,
                'labor' => 3800,
                'overhead' => 5100,
                'totalCost' => 11700,
                'sellingPrice' => 16200,
                'margin' => 4500,
                'marginPercent' => 27.8,
                'status' => 'profitable',
                'trend' => 'stable'
            ],
            [
                'id' => 'p-4',
                'name' => 'Danish',
                'sku' => 'DAN-001',
                'category' => 'Pastry',
                'materials' => 3500,
                'labor' => 4200,
                'overhead' => 6800,
                'totalCost' => 14500,
                'sellingPrice' => 22300,
                'margin' => 7800,
                'marginPercent' => 35.0,
                'status' => 'profitable',
                'trend' => 'up'
            ],
            [
                'id' => 'p-5',
                'name' => 'Multigrain',
                'sku' => 'MUL-001',
                'category' => 'Bread',
                'materials' => 2900,
                'labor' => 3500,
                'overhead' => 4900,
                'totalCost' => 11300,
                'sellingPrice' => 15300,
                'margin' => 4000,
                'marginPercent' => 26.1,
                'status' => 'profitable',
                'trend' => 'stable'
            ],
            [
                'id' => 'p-6',
                'name' => 'Brioche',
                'sku' => 'BRI-001',
                'category' => 'Bread',
                'materials' => 3300,
                'labor' => 4000,
                'overhead' => 5800,
                'totalCost' => 13100,
                'sellingPrice' => 17000,
                'margin' => 3900,
                'marginPercent' => 22.9,
                'status' => 'review',
                'trend' => 'down'
            ],
            [
                'id' => 'p-7',
                'name' => 'Focaccia',
                'sku' => 'FOC-001',
                'category' => 'Bread',
                'materials' => 2400,
                'labor' => 3100,
                'overhead' => 4200,
                'totalCost' => 9700,
                'sellingPrice' => 13000,
                'margin' => 3300,
                'marginPercent' => 25.4,
                'status' => 'profitable',
                'trend' => 'stable'
            ],
            [
                'id' => 'p-8',
                'name' => 'Pain au Chocolat',
                'sku' => 'PAI-001',
                'category' => 'Pastry',
                'materials' => 3800,
                'labor' => 4800,
                'overhead' => 7200,
                'totalCost' => 15800,
                'sellingPrice' => 21000,
                'margin' => 5200,
                'marginPercent' => 24.8,
                'status' => 'review',
                'trend' => 'down'
            ],
        ];

        // Cost Comparison Data
        $costComparisonData = [
            ['name' => 'Baguette', 'materials' => 24, 'labor' => 30, 'overhead' => 46],
            ['name' => 'Croissant', 'materials' => 23, 'labor' => 32, 'overhead' => 45],
            ['name' => 'Sourdough', 'materials' => 24, 'labor' => 32, 'overhead' => 44],
            ['name' => 'Danish', 'materials' => 24, 'labor' => 29, 'overhead' => 47],
            ['name' => 'Multigrain', 'materials' => 26, 'labor' => 31, 'overhead' => 43],
        ];

        // Profitability Trend Data
        $profitabilityTrend = [
            ['month' => 'Jan', 'baguette' => 24, 'croissant' => 30, 'danish' => 33, 'sourdough' => 26],
            ['month' => 'Feb', 'baguette' => 24.5, 'croissant' => 31, 'danish' => 34, 'sourdough' => 27],
            ['month' => 'Mar', 'baguette' => 25, 'croissant' => 31.5, 'danish' => 34.5, 'sourdough' => 27.5],
            ['month' => 'Apr', 'baguette' => 24.8, 'croissant' => 32, 'danish' => 35, 'sourdough' => 27.8],
            ['month' => 'May', 'baguette' => 25, 'croissant' => 32, 'danish' => 35, 'sourdough' => 28],
            ['month' => 'Jun', 'baguette' => 25, 'croissant' => 32.2, 'danish' => 35, 'sourdough' => 27.8],
        ];

        // Scenario Comparison Data
        $scenarioComparison = [
            ['scenario' => 'Current State', 'baguette' => 10500, 'croissant' => 13900, 'danish' => 14500, 'avgMargin' => 28.3],
            ['scenario' => 'Reduce Setup Time', 'baguette' => 10200, 'croissant' => 13500, 'danish' => 14100, 'avgMargin' => 29.8],
            ['scenario' => 'Optimize Energy', 'baguette' => 10300, 'croissant' => 13600, 'danish' => 14200, 'avgMargin' => 29.2],
            ['scenario' => 'Improve QC', 'baguette' => 10400, 'croissant' => 13700, 'danish' => 14300, 'avgMargin' => 28.9],
        ];

        return view('overheadManagement.productCostingAnalysis', compact('productCostData', 'costComparisonData', 'profitabilityTrend', 'scenarioComparison'));
    }

    public function budgetPlanningIndex()
    {
        // Mock Data for Budget Periods
        $budgetPeriods = [
            [
                'id' => '1',
                'name' => 'FY 2024-2025',
                'startDate' => '2024-04-01',
                'endDate' => '2025-03-31',
                'status' => 'active',
                'totalBudget' => 636000,
                'actualSpend' => 318000,
            ],
            [
                'id' => '2',
                'name' => 'FY 2023-2024',
                'startDate' => '2023-04-01',
                'endDate' => '2024-03-31',
                'status' => 'closed',
                'totalBudget' => 600000,
                'actualSpend' => 595000,
            ],
            [
                'id' => '3',
                'name' => 'Q1 2025',
                'startDate' => '2025-01-01',
                'endDate' => '2025-03-31',
                'status' => 'draft',
                'totalBudget' => 159000,
                'actualSpend' => 0,
            ],
        ];

        // Mock Data for Cost Pool Budgets
        $costPoolBudgets = [
            [
                'id' => '1',
                'costPoolId' => 'cp1',
                'costPoolName' => 'Utilities',
                'budgetAmount' => 180000,
                'actualAmount' => 90000,
                'variance' => -90000,
                'variancePercent' => -50,
                'category' => 'Fixed',
            ],
            [
                'id' => '2',
                'costPoolId' => 'cp2',
                'costPoolName' => 'Rent & Depreciation',
                'budgetAmount' => 144000,
                'actualAmount' => 72000,
                'variance' => -72000,
                'variancePercent' => -50,
                'category' => 'Fixed',
            ],
            [
                'id' => '3',
                'costPoolId' => 'cp3',
                'costPoolName' => 'Quality Control',
                'budgetAmount' => 102000,
                'actualAmount' => 51000,
                'variance' => -51000,
                'variancePercent' => -50,
                'category' => 'Variable',
            ],
            [
                'id' => '4',
                'costPoolId' => 'cp4',
                'costPoolName' => 'Material Handling',
                'budgetAmount' => 108000,
                'actualAmount' => 54000,
                'variance' => -54000,
                'variancePercent' => -50,
                'category' => 'Variable',
            ],
            [
                'id' => '5',
                'costPoolId' => 'cp5',
                'costPoolName' => 'Maintenance',
                'budgetAmount' => 102000,
                'actualAmount' => 51000,
                'variance' => -51000,
                'variancePercent' => -50,
                'category' => 'Variable',
            ],
        ];

        return view('overheadManagement.budgetPlanning', compact('budgetPeriods', 'costPoolBudgets'));
    }

    public function varianceAnalysisIndex()
    {
        // Mock data for variance analysis
        $varianceTrendData = [
            ['month' => 'Jan', 'budget' => 53000, 'actual' => 52500, 'variance' => -500, 'variancePercent' => -0.9],
            ['month' => 'Feb', 'budget' => 53000, 'actual' => 54200, 'variance' => 1200, 'variancePercent' => 2.3],
            ['month' => 'Mar', 'budget' => 53000, 'actual' => 52800, 'variance' => -200, 'variancePercent' => -0.4],
            ['month' => 'Apr', 'budget' => 53000, 'actual' => 55100, 'variance' => 2100, 'variancePercent' => 4.0],
            ['month' => 'May', 'budget' => 53000, 'actual' => 51900, 'variance' => -1100, 'variancePercent' => -2.1],
            ['month' => 'Jun', 'budget' => 53000, 'actual' => 53400, 'variance' => 400, 'variancePercent' => 0.8],
        ];

        $costPoolVariances = [
            [
                'id' => '1',
                'costPool' => 'Utilities',
                'budget' => 15000,
                'actual' => 16200,
                'variance' => 1200,
                'variancePercent' => 8.0,
                'status' => 'warning',
                'reason' => 'Higher electricity consumption due to summer',
            ],
            [
                'id' => '2',
                'costPool' => 'Rent & Depreciation',
                'budget' => 12000,
                'actual' => 12000,
                'variance' => 0,
                'variancePercent' => 0,
                'status' => 'on-track',
                'reason' => 'Fixed cost - as expected',
            ],
            [
                'id' => '3',
                'costPool' => 'Quality Control',
                'budget' => 8500,
                'actual' => 9800,
                'variance' => 1300,
                'variancePercent' => 15.3,
                'status' => 'critical',
                'reason' => 'Additional testing requirements for new product line',
            ],
            [
                'id' => '4',
                'costPool' => 'Material Handling',
                'budget' => 9000,
                'actual' => 8200,
                'variance' => -800,
                'variancePercent' => -8.9,
                'status' => 'favorable',
                'reason' => 'Process efficiency improvements',
            ],
            [
                'id' => '5',
                'costPool' => 'Maintenance',
                'budget' => 8500,
                'actual' => 7200,
                'variance' => -1300,
                'variancePercent' => -15.3,
                'status' => 'favorable',
                'reason' => 'Preventive maintenance reduced breakdowns',
            ],
        ];

        $varianceByCategory = [
            ['category' => 'Fixed Costs', 'budget' => 27000, 'actual' => 28200, 'variance' => 1200],
            ['category' => 'Variable Costs', 'budget' => 26000, 'actual' => 25200, 'variance' => -800],
        ];

        $monthlyDetailedVariance = [
            [
                'month' => 'January',
                'costPools' => [
                    ['name' => 'Utilities', 'budget' => 15000, 'actual' => 14800, 'variance' => -200],
                    ['name' => 'Rent & Depreciation', 'budget' => 12000, 'actual' => 12000, 'variance' => 0],
                    ['name' => 'Quality Control', 'budget' => 8500, 'actual' => 8700, 'variance' => 200],
                    ['name' => 'Material Handling', 'budget' => 9000, 'actual' => 8900, 'variance' => -100],
                    ['name' => 'Maintenance', 'budget' => 8500, 'actual' => 8100, 'variance' => -400],
                ],
            ],
            [
                'month' => 'February',
                'costPools' => [
                    ['name' => 'Utilities', 'budget' => 15000, 'actual' => 15400, 'variance' => 400],
                    ['name' => 'Rent & Depreciation', 'budget' => 12000, 'actual' => 12000, 'variance' => 0],
                    ['name' => 'Quality Control', 'budget' => 8500, 'actual' => 9200, 'variance' => 700],
                    ['name' => 'Material Handling', 'budget' => 9000, 'actual' => 9100, 'variance' => 100],
                    ['name' => 'Maintenance', 'budget' => 8500, 'actual' => 8500, 'variance' => 0],
                ],
            ],
        ];

        // Summary Calculations
        $totalBudget = collect($costPoolVariances)->sum('budget');
        $totalActual = collect($costPoolVariances)->sum('actual');
        $totalVariance = $totalActual - $totalBudget;
        $totalVariancePercent = $totalBudget != 0 ? number_format(($totalVariance / $totalBudget) * 100, 1) : 0;

        $criticalVariancesCount = collect($costPoolVariances)->where('status', 'critical')->count();
        $favorableVariancesCount = collect($costPoolVariances)->where('status', 'favorable')->count();

        return view('overheadManagement.varianceAnalysis', compact(
            'varianceTrendData',
            'costPoolVariances',
            'varianceByCategory',
            'monthlyDetailedVariance',
            'totalBudget',
            'totalActual',
            'totalVariance',
            'totalVariancePercent',
            'criticalVariancesCount',
            'favorableVariancesCount'
        ));
    }

    public function budgetForecastingIndex()
    {
        // Mock data for forecasting
        $historicalData = [
            ['month' => 'Jan 23', 'actual' => 48000],
            ['month' => 'Feb 23', 'actual' => 49500],
            ['month' => 'Mar 23', 'actual' => 50200],
            ['month' => 'Apr 23', 'actual' => 51000],
            ['month' => 'May 23', 'actual' => 49800],
            ['month' => 'Jun 23', 'actual' => 52000],
            ['month' => 'Jul 23', 'actual' => 51500],
            ['month' => 'Aug 23', 'actual' => 53000],
            ['month' => 'Sep 23', 'actual' => 52800],
            ['month' => 'Oct 23', 'actual' => 54000],
            ['month' => 'Nov 23', 'actual' => 53500],
            ['month' => 'Dec 23', 'actual' => 55000],
        ];

        $forecastData = [
            ['month' => 'Jan 24', 'actual' => 55500, 'forecast' => 55200, 'lower' => 53500, 'upper' => 57000],
            ['month' => 'Feb 24', 'actual' => 56200, 'forecast' => 56000, 'lower' => 54200, 'upper' => 57800],
            ['month' => 'Mar 24', 'actual' => 57000, 'forecast' => 56800, 'lower' => 54900, 'upper' => 58700],
            ['month' => 'Apr 24', 'actual' => null, 'forecast' => 57600, 'lower' => 55600, 'upper' => 59600],
            ['month' => 'May 24', 'actual' => null, 'forecast' => 58400, 'lower' => 56300, 'upper' => 60500],
            ['month' => 'Jun 24', 'actual' => null, 'forecast' => 59200, 'lower' => 57000, 'upper' => 61400],
            ['month' => 'Jul 24', 'actual' => null, 'forecast' => 60000, 'lower' => 57700, 'upper' => 62300],
            ['month' => 'Aug 24', 'actual' => null, 'forecast' => 60800, 'lower' => 58400, 'upper' => 63200],
            ['month' => 'Sep 24', 'actual' => null, 'forecast' => 61600, 'lower' => 59100, 'upper' => 64100],
            ['month' => 'Oct 24', 'actual' => null, 'forecast' => 62400, 'lower' => 59800, 'upper' => 65000],
            ['month' => 'Nov 24', 'actual' => null, 'forecast' => 63200, 'lower' => 60500, 'upper' => 65900],
            ['month' => 'Dec 24', 'actual' => null, 'forecast' => 64000, 'lower' => 61200, 'upper' => 66800],
        ];

        $costPoolForecasts = [
            [
                'id' => '1',
                'costPool' => 'Utilities',
                'currentMonthly' => 15000,
                'forecastMonthly' => 16200,
                'growthRate' => 8.0,
                'confidence' => 'high',
                'driver' => 'Production volume increase',
            ],
            [
                'id' => '2',
                'costPool' => 'Rent & Depreciation',
                'currentMonthly' => 12000,
                'forecastMonthly' => 12000,
                'growthRate' => 0,
                'confidence' => 'high',
                'driver' => 'Fixed cost - no change',
            ],
            [
                'id' => '3',
                'costPool' => 'Quality Control',
                'currentMonthly' => 8500,
                'forecastMonthly' => 9350,
                'growthRate' => 10.0,
                'confidence' => 'medium',
                'driver' => 'New product testing requirements',
            ],
            [
                'id' => '4',
                'costPool' => 'Material Handling',
                'currentMonthly' => 9000,
                'forecastMonthly' => 9720,
                'growthRate' => 8.0,
                'confidence' => 'medium',
                'driver' => 'Volume growth projection',
            ],
            [
                'id' => '5',
                'costPool' => 'Maintenance',
                'currentMonthly' => 8500,
                'forecastMonthly' => 8925,
                'growthRate' => 5.0,
                'confidence' => 'high',
                'driver' => 'Equipment aging factor',
            ],
        ];

        $scenarioComparison = [
            [
                'scenario' => 'Conservative',
                'q1' => 165000,
                'q2' => 168000,
                'q3' => 171000,
                'q4' => 174000,
                'total' => 678000,
            ],
            [
                'scenario' => 'Base Case',
                'q1' => 171000,
                'q2' => 177000,
                'q3' => 183000,
                'q4' => 189000,
                'total' => 720000,
            ],
            [
                'scenario' => 'Growth',
                'q1' => 180000,
                'q2' => 189000,
                'q3' => 198000,
                'q4' => 207000,
                'total' => 774000,
            ],
        ];

        return view('overheadManagement.budgetForecasting', compact('historicalData', 'forecastData', 'costPoolForecasts', 'scenarioComparison'));
    }

    public function glAccountMappingIndex()
    {
        // Integration Status Data
        $integrationStatus = [
            'isMappingComplete' => false,
            'mappedPools' => 7,
            'totalPools' => 10,
            'mappedExpenses' => 45,
            'totalExpenses' => 50,
            'unmappedPools' => ['Electricity HQ', 'Water Supply Branch B', 'Internet Services Main']
        ];

        // Chart of Accounts (filtered)
        $glAccounts = [
            ['id' => '1', 'code' => '5100-001', 'name' => 'Electricity Expense', 'type' => 'expense', 'isActive' => true],
            ['id' => '2', 'code' => '5100-002', 'name' => 'Water Expense', 'type' => 'expense', 'isActive' => true],
            ['id' => '3', 'code' => '5200-001', 'name' => 'Rent Expense', 'type' => 'expense', 'isActive' => true],
            ['id' => '4', 'code' => '1300-005', 'name' => 'Prepaid Utilities', 'type' => 'asset', 'isActive' => true],
            ['id' => '5', 'code' => '5300-001', 'name' => 'Salaries & Wages', 'type' => 'expense', 'isActive' => true],
        ];

        // Expense Categories Definitions
        $expenseCategories = [
            ['category' => 'utilities', 'label' => 'Utilities', 'description' => 'Electricity, water, gas, internet'],
            ['category' => 'rent', 'label' => 'Rent & Facility', 'description' => 'Rent, property taxes, building maintenance'],
            ['category' => 'salaries', 'label' => 'Salaries', 'description' => 'Administrative and indirect labor salaries'],
            ['category' => 'equipment', 'label' => 'Equipment', 'description' => 'Equipment purchases and depreciation'],
            ['category' => 'maintenance', 'label' => 'Maintenance', 'description' => 'Equipment and facility maintenance'],
        ];

        // Current Mappings (from DB)
        $mappings = [
            'utilities' => ['glExpenseAccountId' => '1', 'glAllocationAccountId' => '4'],
            'rent' => ['glExpenseAccountId' => '3', 'glAllocationAccountId' => null],
        ];

        return view('overheadManagement.glAccountMapping', compact('integrationStatus', 'glAccounts', 'expenseCategories', 'mappings'));
    }
}
