<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DayEndProcessManagementController extends Controller
{
    public function dayEndProcessIndex()
    {
        $today = now()->format('Y-m-d');
        $location = 'HQ-CMB03';

        // 1. Detailed Summaries
        $summary = [
            'salesSummary' => [
                'totalSales' => 145200.00,
                'totalTransactions' => 124,
                'avgTransactionValue' => 1170.96,
                'cashSales' => 45000.00,
                'cardSales' => 85200.00,
                'mobileSales' => 15000.00
            ],
            'cashSummary' => [
                'openingFloat' => 10000.00,
                'cashSales' => 45000.00,
                'cashRefunds' => 0,
                'expectedCash' => 55000.00,
                'actualCash' => 0,
                'variance' => 0,
                'depositAmount' => 45000.00
            ],
            'productionSummary' => [
                'batchesCompleted' => 18,
                'batchesScheduled' => 20,
                'wastageRecorded' => true
            ],
            'financeSummary' => [
                'journalEntriesPosted' => 12,
                'journalEntriesPending' => 3,
                'trialBalanceStatus' => 'balanced' // or 'unbalanced'
            ],
            // Overall task stats are calculated in the view or here
            'progress' => 0,
        ];

        // 2. Checklist Tasks
        $tasks = [
            // Sales Category
            [
                'id' => 'verify-sales',
                'category' => 'sales',
                'title' => 'Verify POS Sales',
                'desc' => 'Compare physical receipts with system totals.',
                'status' => 'in-progress',
                'priority' => 'critical',
                'notes' => '',
                'errorMessage' => null,
                'completedAt' => null
            ],
            [
                'id' => 'check-discounts',
                'category' => 'sales',
                'title' => 'Review Discounts',
                'desc' => 'Validate manager overrides and discounts > 10%.',
                'status' => 'pending',
                'priority' => 'important',
                'notes' => '',
                'errorMessage' => null,
                'completedAt' => null
            ],

            // Cash Category
            [
                'id' => 'count-drawer',
                'category' => 'cash',
                'title' => 'Count Cash Drawer',
                'desc' => 'Physical count of all denominations.',
                'status' => 'pending',
                'priority' => 'critical',
                'notes' => '',
                'errorMessage' => null,
                'completedAt' => null
            ],
            [
                'id' => 'cash-drop',
                'category' => 'cash',
                'title' => 'Prepare Bank Deposit',
                'desc' => 'Bag cash exceeding float limit for deposit.',
                'status' => 'pending',
                'priority' => 'critical',
                'notes' => '',
                'errorMessage' => null,
                'completedAt' => null
            ],

            // Inventory Category
            [
                'id' => 'stock-audit',
                'category' => 'inventory',
                'title' => 'High-Value Stock Count',
                'desc' => 'Verify counts for premium ingredients.',
                'status' => 'completed',
                'priority' => 'important',
                'notes' => 'All matches found.',
                'errorMessage' => null,
                'completedAt' => now()->subHours(2)->toDateTimeString(),
                'completedBy' => 'Manager'
            ],

            // Production Category
            [
                'id' => 'verify-waste',
                'category' => 'production',
                'title' => 'Verify Waste Logs',
                'desc' => 'Ensure all Stage 3 waste items are recorded.',
                'status' => 'completed',
                'priority' => 'important',
                'notes' => 'Checked with Head Baker.',
                'errorMessage' => null,
                'completedAt' => now()->subHour()->toDateTimeString(),
                'completedBy' => 'Supervisor'
            ],

            // Finance Category
            [
                'id' => 'post-journal',
                'category' => 'finance',
                'title' => 'Post Journal Entries',
                'desc' => 'Finalize daily automated journal entries.',
                'status' => 'pending',
                'priority' => 'critical',
                'notes' => '',
                'errorMessage' => null, // e.g., 'Trial balance unbalanced'
                'completedAt' => null
            ]
        ];

        // 3. Process Logic
        $totalTasks = count($tasks);
        $completedTasks = collect($tasks)->where('status', 'completed')->count();
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $summary['progress'] = $progress; // Logic is simplified for dummy data

        $isLocked = false;
        $readyToClose = false;
        $blockers = ['Verify POS Sales', 'Count Cash Drawer', 'Prepare Bank Deposit', 'Post Journal Entries'];

        return view('dayEnd.dayEndProcess', compact('today', 'location', 'summary', 'tasks', 'isLocked', 'readyToClose', 'blockers'));
    }
}
