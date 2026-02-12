<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WasteRecoveryManagementController extends Controller
{
    public function wasteRecoveryConfigurationIndex()
    {
        // Mock Configuration
        $config = (object) [
            'freshProductDays' => 1,
            'dayOldDays' => 2,
            'wasteThresholdDays' => 3,
            'dayOldPricePercent' => 45,
            'wasteRecoveryPercent' => 12,
            'autoTransferToWaste' => true,
            'autoCalculateNRV' => true,
            'requireApprovalForDisposal' => true,
        ];

        // Mock Recovery Methods
        $recoveryMethods = [
            (object) [
                'id' => 1,
                'method' => 'animal_feed',
                'name' => 'Animal Feed',
                'description' => 'Process into animal feed pellets for local farms',
                'isActive' => true,
                'requiresApproval' => false,
                'nrvPerKg' => 18.50,
                'processingCostPerKg' => 4.20,
                'co2OffsetPerKg' => 2.8,
                'environmentalBenefit' => 'Supports local agriculture',
            ],
            (object) [
                'id' => 2,
                'method' => 'compost',
                'name' => 'Composting',
                'description' => 'Industrial composting for organic fertilizer',
                'isActive' => true,
                'requiresApproval' => false,
                'nrvPerKg' => 9.50,
                'processingCostPerKg' => 2.80,
                'co2OffsetPerKg' => 4.2,
                'environmentalBenefit' => 'Soil regeneration',
            ],
            (object) [
                'id' => 3,
                'method' => 'biogas',
                'name' => 'Bio-Gas Generation',
                'description' => 'Anaerobic digestion for renewable energy',
                'isActive' => true, // Changed to active for variety
                'requiresApproval' => true,
                'nrvPerKg' => 14.20,
                'processingCostPerKg' => 7.50,
                'co2OffsetPerKg' => 6.1,
                'environmentalBenefit' => 'Green energy production',
            ],
            (object) [
                'id' => 4,
                'method' => 'donation', // New method
                'name' => 'Charity Donation',
                'description' => 'Donation to local food banks (edible only)',
                'isActive' => true,
                'requiresApproval' => true,
                'nrvPerKg' => 0.00, // No revenue, but tax write-off potential (simplified here)
                'processingCostPerKg' => 1.50, // Transport cost
                'co2OffsetPerKg' => 3.5,
                'environmentalBenefit' => 'Community support',
            ],
            (object) [
                'id' => 5,
                'method' => 'disposal',
                'name' => 'Landfill Disposal',
                'description' => 'Standard waste disposal (Last resort)',
                'isActive' => false,
                'requiresApproval' => true,
                'nrvPerKg' => 0.00,
                'processingCostPerKg' => 12.00, // High cost
                'co2OffsetPerKg' => 0,
                'environmentalBenefit' => null,
            ],
        ];

        // Mock Product Profiles (Computed NRV for display)
        $productProfiles = [
            (object) [
                'id' => 1,
                'productName' => 'Sourdough Loaf',
                'productCode' => 'BRD-SD-001',
                'shelfLifeDays' => 2,
                'originalSellingPrice' => 180.00,
                'dayOldSellingPrice' => 81.00, // 45%
                'wasteRecoveryValue' => 21.60, // 12%
                'preferredRecoveryMethod' => 'Animal Feed',
                'isActive' => true,
            ],
            (object) [
                'id' => 2,
                'productName' => 'Whole Wheat Bread',
                'productCode' => 'BRD-WW-002',
                'shelfLifeDays' => 3,
                'originalSellingPrice' => 140.00,
                'dayOldSellingPrice' => 63.00,
                'wasteRecoveryValue' => 16.80,
                'preferredRecoveryMethod' => 'Composting',
                'isActive' => true,
            ],
            (object) [
                'id' => 3,
                'productName' => 'Butter Croissant',
                'productCode' => 'PST-CR-001',
                'shelfLifeDays' => 1,
                'originalSellingPrice' => 95.00,
                'dayOldSellingPrice' => 42.75,
                'wasteRecoveryValue' => 11.40,
                'preferredRecoveryMethod' => 'Bio-Gas',
                'isActive' => true,
            ],
            (object) [
                'id' => 4,
                'productName' => 'Chocolate Muffin',
                'productCode' => 'PST-MF-005',
                'shelfLifeDays' => 2,
                'originalSellingPrice' => 120.00,
                'dayOldSellingPrice' => 54.00,
                'wasteRecoveryValue' => 14.40,
                'preferredRecoveryMethod' => 'Charity Donation',
                'isActive' => true,
            ],
            (object) [
                'id' => 5,
                'productName' => 'Baguette',
                'productCode' => 'BRD-BG-003',
                'shelfLifeDays' => 1,
                'originalSellingPrice' => 110.00,
                'dayOldSellingPrice' => 49.50,
                'wasteRecoveryValue' => 13.20,
                'preferredRecoveryMethod' => 'Animal Feed',
                'isActive' => true,
            ],
            (object) [
                'id' => 6,
                'productName' => 'Cinnamon Roll',
                'productCode' => 'PST-CN-008',
                'shelfLifeDays' => 2,
                'originalSellingPrice' => 160.00,
                'dayOldSellingPrice' => 72.00,
                'wasteRecoveryValue' => 19.20,
                'preferredRecoveryMethod' => 'Bio-Gas',
                'isActive' => false,
            ],
            (object) [
                'id' => 7,
                'productName' => 'Rye Bread',
                'productCode' => 'BRD-RY-004',
                'shelfLifeDays' => 4,
                'originalSellingPrice' => 190.00,
                'dayOldSellingPrice' => 85.50,
                'wasteRecoveryValue' => 22.80,
                'preferredRecoveryMethod' => 'Composting',
                'isActive' => true,
            ],
            (object) [
                'id' => 8,
                'productName' => 'Fruit Danish',
                'productCode' => 'PST-DN-012',
                'shelfLifeDays' => 1,
                'originalSellingPrice' => 135.00,
                'dayOldSellingPrice' => 60.75,
                'wasteRecoveryValue' => 16.20,
                'preferredRecoveryMethod' => 'Animal Feed',
                'isActive' => true,
            ],
        ];

        // Mock Stats
        $stats = (object) [
            'activeRecoveryMethods' => collect($recoveryMethods)->where('isActive', true)->count(),
            'totalRecoveryMethods' => count($recoveryMethods),
            'activeProductProfiles' => collect($productProfiles)->where('isActive', true)->count(),
            'averageShelfLife' => collect($productProfiles)->avg('shelfLifeDays'),
            'configActive' => true,
        ];

        // Mock Method Comparison (Calculated)
        $methodComparison = collect($recoveryMethods)->map(function ($m) {
            return (object) [
                'method' => $m->method,
                'name' => $m->name,
                'nrvPerKg' => $m->nrvPerKg,
                'costPerKg' => $m->processingCostPerKg,
                'netPerKg' => $m->nrvPerKg - $m->processingCostPerKg,
                'isActive' => $m->isActive,
            ];
        })->values()->all();

        return view('wasteRecovery.configuration', compact('config', 'stats', 'recoveryMethods', 'methodComparison', 'productProfiles'));
    }

    public function wasteTrackingIndex()
    {
        // Mock Recovery Methods (Reused from logic)
        $recoveryMethods = [
            (object) ['id' => 1, 'method' => 'animal_feed', 'name' => 'Animal Feed', 'description' => 'Process into animal feed pellets', 'nrvPerKg' => 18.50, 'processingCostPerKg' => 4.20, 'isActive' => true],
            (object) ['id' => 2, 'method' => 'compost', 'name' => 'Composting', 'description' => 'Industrial composting', 'nrvPerKg' => 9.50, 'processingCostPerKg' => 2.80, 'isActive' => true],
            (object) ['id' => 3, 'method' => 'biogas', 'name' => 'Bio-Gas Generation', 'description' => 'Anaerobic digestion', 'nrvPerKg' => 14.20, 'processingCostPerKg' => 7.50, 'isActive' => true],
        ];

        // Mock Product Profiles for frontend constants/calculations
        $productProfiles = [
            'BAKE-001' => ['dayOldStartDay' => 1, 'wasteThresholdDay' => 3],
            'BAKE-002' => ['dayOldStartDay' => 2, 'wasteThresholdDay' => 4],
            'BAKE-003' => ['dayOldStartDay' => 1, 'wasteThresholdDay' => 2],
        ];

        // Dummy Tracking Records
        $trackingRecords = [
            (object) ['id' => 1, 'trackingNumber' => 'TRK-2024-001', 'productName' => 'Sourdough Bread', 'productCode' => 'BAKE-001', 'days_old' => 1, 'currentStage' => 'fresh', 'quantity' => 20, 'unitOfMeasure' => 'pcs', 'currentValue' => 100.00, 'originalCost' => 100.00, 'totalNRVWritedown' => 0, 'productionDate' => date('Y-m-d', strtotime('-1 day'))],
            (object) ['id' => 2, 'trackingNumber' => 'TRK-2024-005', 'productName' => 'Whole Wheat Loaf', 'productCode' => 'BAKE-002', 'days_old' => 2, 'currentStage' => 'day-old', 'quantity' => 15, 'unitOfMeasure' => 'pcs', 'currentValue' => 45.00, 'originalCost' => 75.00, 'totalNRVWritedown' => 30.00, 'productionDate' => date('Y-m-d', strtotime('-2 days'))],
            (object) ['id' => 3, 'trackingNumber' => 'TRK-2024-009', 'productName' => 'Artisan Baguette', 'productCode' => 'BAKE-003', 'days_old' => 4, 'currentStage' => 'waste', 'quantity' => 10, 'unitOfMeasure' => 'pcs', 'currentValue' => 5.00, 'originalCost' => 40.00, 'totalNRVWritedown' => 35.00, 'productionDate' => date('Y-m-d', strtotime('-4 days'))],
            (object) ['id' => 4, 'trackingNumber' => 'TRK-2024-012', 'productName' => 'Chocolate Croissant', 'productCode' => 'PAST-001', 'days_old' => 1, 'currentStage' => 'fresh', 'quantity' => 30, 'unitOfMeasure' => 'pcs', 'currentValue' => 120.00, 'originalCost' => 120.00, 'totalNRVWritedown' => 0, 'productionDate' => date('Y-m-d', strtotime('-1 day'))],
            (object) ['id' => 5, 'trackingNumber' => 'TRK-2024-015', 'productName' => 'Cinnamon Roll', 'productCode' => 'PAST-002', 'days_old' => 3, 'currentStage' => 'day-old', 'quantity' => 12, 'unitOfMeasure' => 'pcs', 'currentValue' => 24.00, 'originalCost' => 48.00, 'totalNRVWritedown' => 24.00, 'productionDate' => date('Y-m-d', strtotime('-3 days'))],
        ];

        // Dummy Alerts
        $alerts = [
            (object) ['trackingId' => 3, 'trackingNumber' => 'TRK-2024-009', 'productName' => 'Artisan Baguette', 'urgency' => 'critical', 'message' => 'Item exceeded max waste hold time', 'daysOld' => 4, 'quantity' => 10, 'currentValue' => 5.00, 'potentialLoss' => 40.00, 'recommendedAction' => 'process-now'],
            (object) ['trackingId' => 5, 'trackingNumber' => 'TRK-2024-015', 'productName' => 'Cinnamon Roll', 'urgency' => 'high', 'message' => 'Approaching waste threshold', 'daysOld' => 3, 'quantity' => 12, 'currentValue' => 24.00, 'potentialLoss' => 24.00, 'recommendedAction' => 'transfer-to-waste'],
            (object) ['trackingId' => 2, 'trackingNumber' => 'TRK-2024-005', 'productName' => 'Whole Wheat Loaf', 'urgency' => 'medium', 'message' => 'Review for potential day-old sale', 'daysOld' => 2, 'quantity' => 15, 'currentValue' => 45.00, 'potentialLoss' => 30.00, 'recommendedAction' => 'transfer-to-day-old'],
        ];

        // Calculate Stats
        $stats = [
            'activeRecords' => count($trackingRecords),
            'financial' => [
                'totalNRVWritedown' => array_sum(array_column($trackingRecords, 'totalNRVWritedown')),
                'netWasteLoss' => 840.25, // Mocked for now as we don't have full recovery history in this simple view
            ],
            'averageRecoveryEfficiency' => 68.5,
            'byStage' => [
                'fresh' => count(array_filter($trackingRecords, fn($r) => $r->currentStage === 'fresh')),
                'dayOld' => count(array_filter($trackingRecords, fn($r) => $r->currentStage === 'day-old')),
                'waste' => count(array_filter($trackingRecords, fn($r) => $r->currentStage === 'waste')),
                'recovered' => 25,
                'disposed' => 8
            ]
        ];

        return view('wasteRecovery.wasteTracking', compact('stats', 'trackingRecords', 'alerts', 'recoveryMethods', 'productProfiles'));
    }

    public function wasteRecoveryDashboardIndex()
    {
        // Today's Status Summary
        $todayStatus = [
            'fresh' => 12,
            'dayOld' => 4,
            'waste' => 3,
            'needDayOldTransfer' => 2,
            'needWasteTransfer' => 1,
            'formattedDate' => now()->format('l, F j, Y')
        ];

        // Key Financial Stats
        $stats = [
            'financial' => [
                'totalOriginalValue' => 12500.00,
                'totalNRVWritedown' => 1850.50,
                'totalWasteLoss' => 2400.00,
                'totalRecoveryIncome' => 840.25,
            ],
            'averageRecoveryEfficiency' => 35.0
        ];

        // Recovery Method Performance Table
        $wasteSummary = [
            'animal-feed' => ['name' => 'Animal Feed', 'count' => 12, 'input' => 120.5, 'output' => 110.0, 'revenue' => 450.00, 'cost' => 60.00, 'net' => 390.00],
            'compost' => ['name' => 'Compost', 'count' => 8, 'input' => 85.0, 'output' => 40.0, 'revenue' => 120.00, 'cost' => 25.00, 'net' => 95.00],
            'bio-gas' => ['name' => 'Bio-Gas', 'count' => 5, 'input' => 90.0, 'output' => 0, 'revenue' => 75.00, 'cost' => 15.00, 'net' => 60.00],
        ];

        // Product Waste Analysis Table
        $productAnalysis = [
            ['productName' => 'Sourdough Bread', 'totalProduced' => 1000, 'totalWaste' => 120, 'wastePercentage' => 12.0, 'totalOriginalCost' => 2500, 'totalRecoveryValue' => 300, 'totalNetLoss' => 2200],
            ['productName' => 'Artisan Croissant', 'totalProduced' => 500, 'totalWaste' => 45, 'wastePercentage' => 9.0, 'totalOriginalCost' => 1500, 'totalRecoveryValue' => 150, 'totalNetLoss' => 1350],
            ['productName' => 'Baguette', 'totalProduced' => 1500, 'totalWaste' => 30, 'wastePercentage' => 2.0, 'totalOriginalCost' => 1500, 'totalRecoveryValue' => 50, 'totalNetLoss' => 1450],
        ];

        // Trends Data (Last 6 Months)
        $wasteTrends = [
            ['month' => 'Jul 2025', 'production' => 42000, 'wasteValue' => 4800, 'wastePercent' => 11.4, 'recoveryIncome' => 600, 'netLoss' => 4200, 'trendUp' => false],
            ['month' => 'Aug 2025', 'production' => 44000, 'wasteValue' => 4600, 'wastePercent' => 10.5, 'recoveryIncome' => 580, 'netLoss' => 4020, 'trendUp' => false],
            ['month' => 'Sep 2025', 'production' => 41000, 'wasteValue' => 4100, 'wastePercent' => 10.0, 'recoveryIncome' => 520, 'netLoss' => 3580, 'trendUp' => false],
            ['month' => 'Oct 2025', 'production' => 46000, 'wasteValue' => 4200, 'wastePercent' => 9.1, 'recoveryIncome' => 550, 'netLoss' => 3650, 'trendUp' => false],
            ['month' => 'Nov 2025', 'production' => 45000, 'wasteValue' => 3900, 'wastePercent' => 8.7, 'recoveryIncome' => 500, 'netLoss' => 3400, 'trendUp' => false],
            ['month' => 'Dec 2025', 'production' => 52000, 'wasteValue' => 5200, 'wastePercent' => 10.0, 'recoveryIncome' => 680, 'netLoss' => 4520, 'trendUp' => true],
        ];

        // Product Waste Analysis Table Data
        $productAnalysis = [
            [
                'productName' => 'Sourdough Bread',
                'totalProduced' => 1200,
                'totalWaste' => 144,
                'wastePercentage' => 12.0,
                'totalOriginalCost' => 2400.00,
                'totalRecoveryValue' => 120.00,
                'totalNetLoss' => 2280.00,
            ],
            [
                'productName' => 'Croissants',
                'totalProduced' => 800,
                'totalWaste' => 48,
                'wastePercentage' => 6.0,
                'totalOriginalCost' => 1600.00,
                'totalRecoveryValue' => 80.00,
                'totalNetLoss' => 1520.00,
            ],
            [
                'productName' => 'Baguettes',
                'totalProduced' => 1500,
                'totalWaste' => 60,
                'wastePercentage' => 4.0,
                'totalOriginalCost' => 1500.00,
                'totalRecoveryValue' => 30.00,
                'totalNetLoss' => 1470.00,
            ]
        ];

        return view('wasteRecovery.dashboard', compact('todayStatus', 'stats', 'wasteSummary', 'productAnalysis', 'wasteTrends'));
    }

    public function wasteRecoveryReportsIndex()
    {
        // 1. Waste P&L Data
        $wastePL = [
            'beginningInventory' => 150000,
            'productionCosts' => 458000,
            'endingInventory' => 124000,
            'costOfWasteItems' => 484000,
            'dayOldSales' => 240000,
            'wasteRecoveryIncome' => 52000,
            'totalRevenue' => 292000,
            'nrvWritedowns' => 85000,    // Stage 1 -> 2 write-down
            'wasteLoss' => 124000,      // Total loss for items that reached Stage 3
            'processingCosts' => 32000, // Cost to convert waste to recovery output
            'disposalCosts' => 15000,   // Cost for landfill/disposal
            'totalExpenses' => 256000,
            'netWasteLoss' => 192000,
            'recoveryRate' => 48.5
        ];

        // 2. Category Data
        $categories = [
            'Bread Products' => ['prod' => 250000, 'waste' => 28000, 'pct' => 11.2, 'rec' => 4800, 'net' => 23200],
            'Cakes & Pastries' => ['prod' => 150000, 'waste' => 15000, 'pct' => 10.0, 'rec' => 800, 'net' => 14200],
            'Cookies' => ['prod' => 50000, 'waste' => 2000, 'pct' => 4.0, 'rec' => 0, 'net' => 2000],
        ];

        // 3. Environmental Impact
        $envImpact = [
            'diversionRate' => 87.0,
            'wasteDiverted' => 570.5,
            'wasteToLandfill' => 85.2,
            'totalCO2Offset' => 245.0,
            'animalFeedProduced' => 450.0,
            'compostProduced' => 120.0,
        ];

        $efficiencyKPIs = [
            'wasteProductionRatio' => ['actual' => 10.0, 'target' => 8.0, 'status' => 'warning'],
            'recoveryEfficiency' => ['actual' => 12.4, 'target' => 20.0, 'status' => 'critical'],
            'netLossRevenueRatio' => ['actual' => 8.8, 'target' => 6.0, 'status' => 'critical'],
            'dayOldConversion' => ['actual' => 65.0, 'target' => 75.0, 'status' => 'warning'],
            'avgDaysToRecovery' => ['actual' => 3.2, 'target' => 3.0, 'status' => 'warning'],
            'processingTime' => ['actual' => 2.5, 'target' => 2.0, 'status' => 'warning'],
            'disposalRate' => ['actual' => 19.0, 'target' => 15.0, 'status' => 'critical']
        ];

        return view('wasteRecovery.reports', compact('wastePL', 'categories', 'envImpact', 'efficiencyKPIs'));
    }

    public function wasteRecoveryAutomationIndex()
    {
        // Integration Statistics
        $stats = [
            'activeRules' => 5,
            'totalRules' => 8,
            'totalRuleExecutions' => 1248,
            'activeAlerts' => 3,
            'processedEvents' => 5420
        ];

        // Automation Rules Dummy Data
        $rules = [
            (object) [
                'id' => 'rule_001',
                'name' => 'Auto-Transfer to Day-Old',
                'description' => 'Automatically move fresh items to day-old stage when shelf-life threshold is met.',
                'isActive' => true,
                'trigger' => 'time-based',
                'priority' => 1,
                'lastExecuted' => now()->subHours(2),
                'executionCount' => 450,
                'conditions' => [['type' => 'shelf-life', 'operator' => '>=', 'value' => '100%']],
                'actions' => [['type' => 'stage-transition', 'targetStage' => 'day-old']],
                'createdBy' => 'System Admin',
                'createdAt' => now()->subMonths(3)
            ],
            (object) [
                'id' => 'rule_002',
                'name' => 'High Waste Alert',
                'description' => 'Notify production manager when waste for a specific batch exceeds 15%.',
                'isActive' => true,
                'trigger' => 'threshold-based',
                'priority' => 2,
                'lastExecuted' => now()->subDays(1),
                'executionCount' => 12,
                'conditions' => [['type' => 'waste-rate', 'operator' => '>', 'value' => '15%']],
                'actions' => [['type' => 'send-alert', 'alertRecipients' => ['Manager', 'Production Head']]],
                'createdBy' => 'Finance Dept',
                'createdAt' => now()->subMonths(1)
            ],
            (object) [
                'id' => 'rule_003',
                'name' => 'Nightly Ledger Sync',
                'description' => 'Sync all recovery income to the main financial ledger at 11:59 PM.',
                'isActive' => false,
                'trigger' => 'time-based',
                'priority' => 3,
                'lastExecuted' => null,
                'executionCount' => 0,
                'conditions' => [['type' => 'time', 'operator' => 'at', 'value' => '23:59']],
                'actions' => [['type' => 'financial-sync']],
                'createdBy' => 'Accounting',
                'createdAt' => now()->subWeeks(2)
            ]
        ];

        // Active System Alerts
        $alerts = [
            (object) [
                'id' => 'alt_01',
                'severity' => 'critical',
                'title' => 'Processing Delay',
                'message' => 'Batch #882 has been in Stage 3 (Waste) for over 48 hours without recovery processing.',
                'createdAt' => now()->subHours(4),
                'productCode' => 'BAKE-001',
                'actionRequired' => true,
                'actionUrl' => '#'
            ],
            (object) [
                'id' => 'alt_02',
                'severity' => 'warning',
                'title' => 'POS Sync Interrupted',
                'message' => 'The connection to the Retail POS system was lost. Day-old sales may not be syncing.',
                'createdAt' => now()->subHours(1),
                'productCode' => null,
                'actionRequired' => false,
                'actionUrl' => null
            ]
        ];

        return view('wasteRecovery.automation', compact('stats', 'rules', 'alerts'));
    }
}
