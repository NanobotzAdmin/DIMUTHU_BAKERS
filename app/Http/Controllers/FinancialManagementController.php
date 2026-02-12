<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinancialManagementController extends Controller
{
    public function financialManagementOverviewIndex()
    {
        $accounts = $this->getMockGlAccounts();
        $entries = $this->getMockJournalEntries();

        // Calculate Stats
        $stats = [
            'totalAccounts' => count($accounts),
            'activeAccounts' => count(array_filter($accounts, fn($a) => $a['isActive'])),
            'totalEntries' => count($entries),
            'postedEntries' => count(array_filter($entries, fn($e) => $e['status'] === 'posted')),
            'draftEntries' => count(array_filter($entries, fn($e) => $e['status'] === 'draft')),
            'totalAssets' => array_sum(array_column(array_filter($accounts, fn($a) => $a['type'] === 'asset'), 'currentBalance')),
            'totalLiabilities' => array_sum(array_column(array_filter($accounts, fn($a) => $a['type'] === 'liability'), 'currentBalance')),
            'totalEquity' => array_sum(array_column(array_filter($accounts, fn($a) => $a['type'] === 'equity'), 'currentBalance')),
            'totalRevenue' => array_sum(array_column(array_filter($accounts, fn($a) => $a['type'] === 'revenue'), 'currentBalance')),
            'totalExpenses' => array_sum(array_column(array_filter($accounts, fn($a) => $a['type'] === 'expense'), 'currentBalance')),
        ];

        $stats['netProfit'] = $stats['totalRevenue'] - $stats['totalExpenses']; // Simple calc
        $stats['isBalanced'] = true; // Mock balanced state for now

        return view('financialManagement.overview', compact('stats'));
    }

    public function chartOfAccountsIndex()
    {
        $glAccounts = $this->getMockGlAccounts();
        return view('financialManagement.chartOfAccounts', compact('glAccounts'));
    }

    public function journalEntriesIndex()
    {
        $glAccounts = $this->getMockGlAccounts();
        $journalEntries = $this->getMockJournalEntries();

        return view('financialManagement.journalEntries', compact('glAccounts', 'journalEntries'));
    }

    private function getMockJournalEntries()
    {
        return [
            [
                'id' => 'JE-101',
                'entryNumber' => 'JE-2024-001',
                'reference' => 'Invoice #1001',
                'date' => '2024-01-15',
                'description' => 'Sales Invoice - Bakery Delight',
                'notes' => 'Monthly supply',
                'type' => 'sales',
                'status' => 'posted',
                'createdBy' => 'System',
                'createdAt' => '2024-01-15 10:00:00',
                'postedAt' => '2024-01-15 10:05:00',
                'lines' => [
                    ['accountId' => 3, 'accountCode' => '1200', 'accountName' => 'Accounts Receivable', 'description' => 'Sales Invoice', 'debit' => 500.00, 'credit' => 0.00],
                    ['accountId' => 12, 'accountCode' => '4000', 'accountName' => 'Sales Revenue', 'description' => 'Sales Revenue', 'debit' => 0.00, 'credit' => 500.00],
                ]
            ],
            [
                'id' => 'JE-102',
                'entryNumber' => 'JE-2024-002',
                'reference' => 'PO #500',
                'date' => '2024-01-16',
                'description' => 'Inventory Purchase - Flour',
                'notes' => 'Restock',
                'type' => 'purchase',
                'status' => 'draft',
                'createdBy' => 'John Doe',
                'createdAt' => '2024-01-16 11:00:00',
                'postedAt' => null,
                'lines' => [
                    ['accountId' => 4, 'accountCode' => '1400', 'accountName' => 'Inventory', 'description' => 'Bulk Flour', 'debit' => 1000.00, 'credit' => 0.00],
                    ['accountId' => 7, 'accountCode' => '2000', 'accountName' => 'Accounts Payable', 'description' => 'Vendor payment pending', 'debit' => 0.00, 'credit' => 1000.00],
                ]
            ],
            [
                'id' => 'JE-103',
                'entryNumber' => 'JE-2024-003',
                'reference' => 'Ref-003',
                'date' => '2024-01-18',
                'description' => 'Utility Bill Payment',
                'notes' => 'Electric Bill',
                'type' => 'payment',
                'status' => 'posted',
                'createdBy' => 'Admin',
                'createdAt' => '2024-01-18 09:00:00',
                'postedAt' => '2024-01-18 09:30:00',
                'lines' => [
                    ['accountId' => 17, 'accountCode' => '6200', 'accountName' => 'Utilities Expense', 'description' => 'Jan Electric', 'debit' => 250.00, 'credit' => 0.00],
                    ['accountId' => 1, 'accountCode' => '1000', 'accountName' => 'Cash & Cash Equivalents', 'description' => 'Cash Payment', 'debit' => 0.00, 'credit' => 250.00],
                ]
            ],
            [
                'id' => 'JE-104',
                'entryNumber' => 'JE-2024-004',
                'reference' => 'REV-001',
                'date' => '2024-02-01',
                'description' => 'Reversal of JE-2024-001',
                'notes' => 'Error in original entry',
                'type' => 'reversal',
                'status' => 'reversed',
                'createdBy' => 'Admin User',
                'createdAt' => '2024-02-01 09:00:00',
                'postedAt' => '2024-02-01 09:00:00',
                'lines' => [
                    ['accountId' => 3, 'accountCode' => '1200', 'accountName' => 'Accounts Receivable', 'description' => 'Reversal', 'debit' => 0.00, 'credit' => 500.00],
                    ['accountId' => 12, 'accountCode' => '4000', 'accountName' => 'Sales Revenue', 'description' => 'Reversal', 'debit' => 500.00, 'credit' => 0.00],
                ],
                'reversedEntryId' => 'JE-101'
            ]
        ];

        return view('financialManagement.journalEntries', compact('glAccounts', 'journalEntries'));
    }



    private function getMockGlAccounts()
    {
        return [
            // Assets
            ['id' => 1, 'code' => '1000', 'name' => 'Cash & Cash Equivalents', 'type' => 'asset', 'category' => 'current-asset', 'subCategory' => 'Cash', 'currentBalance' => 15500, 'openingBalance' => 12500, 'allowManualEntry' => false, 'isActive' => true, 'parentId' => null],
            ['id' => 101, 'code' => '1001', 'name' => 'Petty Cash', 'type' => 'asset', 'category' => 'current-asset', 'subCategory' => 'Cash', 'currentBalance' => 500, 'openingBalance' => 500, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => 1],
            ['id' => 102, 'code' => '1002', 'name' => 'Checking Account', 'type' => 'asset', 'category' => 'current-asset', 'subCategory' => 'Cash', 'currentBalance' => 15000, 'openingBalance' => 12000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => 1],

            ['id' => 3, 'code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'current-asset', 'subCategory' => 'Receivables', 'currentBalance' => 8500, 'openingBalance' => 6000, 'allowManualEntry' => false, 'isActive' => true, 'parentId' => null],
            ['id' => 4, 'code' => '1400', 'name' => 'Inventory', 'type' => 'asset', 'category' => 'current-asset', 'subCategory' => 'Inventory', 'currentBalance' => 12000, 'openingBalance' => 10000, 'allowManualEntry' => false, 'isActive' => true, 'parentId' => null],
            ['id' => 103, 'code' => '1401', 'name' => 'Raw Materials', 'type' => 'asset', 'category' => 'current-asset', 'subCategory' => 'Inventory', 'currentBalance' => 12000, 'openingBalance' => 10000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => 4],

            ['id' => 5, 'code' => '1600', 'name' => 'Machinery & Equipment', 'type' => 'asset', 'category' => 'fixed-asset', 'subCategory' => 'Equipment', 'currentBalance' => 55000, 'openingBalance' => 50000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 6, 'code' => '1650', 'name' => 'Accumulated Depreciation', 'type' => 'asset', 'category' => 'fixed-asset', 'subCategory' => 'Equipment', 'currentBalance' => -5000, 'openingBalance' => -2000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => 5],

            // Liabilities
            ['id' => 7, 'code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'current-liability', 'subCategory' => 'Payables', 'currentBalance' => 6200, 'openingBalance' => 7000, 'allowManualEntry' => false, 'isActive' => true, 'parentId' => null],
            ['id' => 8, 'code' => '2100', 'name' => 'Accrued Expenses', 'type' => 'liability', 'category' => 'current-liability', 'subCategory' => 'Payables', 'currentBalance' => 1200, 'openingBalance' => 1000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 9, 'code' => '2500', 'name' => 'Bank Loan', 'type' => 'liability', 'category' => 'long-term-liability', 'subCategory' => 'Debt', 'currentBalance' => 30000, 'openingBalance' => 35000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],

            // Equity
            ['id' => 10, 'code' => '3000', 'name' => 'Owner Capital', 'type' => 'equity', 'category' => 'equity', 'subCategory' => 'Capital', 'currentBalance' => 45000, 'openingBalance' => 40000, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 11, 'code' => '3200', 'name' => 'Retained Earnings', 'type' => 'equity', 'category' => 'equity', 'subCategory' => 'Earnings', 'currentBalance' => 3600, 'openingBalance' => 3600, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],

            // Revenue
            ['id' => 12, 'code' => '4000', 'name' => 'Sales Revenue', 'type' => 'revenue', 'category' => 'revenue', 'subCategory' => 'Sales', 'currentBalance' => 120000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 13, 'code' => '4100', 'name' => 'Service Revenue', 'type' => 'revenue', 'category' => 'revenue', 'subCategory' => 'Service', 'currentBalance' => 5000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],

            // Expenses
            ['id' => 14, 'code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'expense', 'category' => 'expense', 'subCategory' => 'COGS', 'currentBalance' => 65000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 15, 'code' => '6000', 'name' => 'Rent Expense', 'type' => 'expense', 'category' => 'expense', 'subCategory' => 'Operating', 'currentBalance' => 12000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 16, 'code' => '6100', 'name' => 'Salaries Expense', 'type' => 'expense', 'category' => 'expense', 'subCategory' => 'Operating', 'currentBalance' => 25000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 17, 'code' => '6200', 'name' => 'Utilities Expense', 'type' => 'expense', 'category' => 'expense', 'subCategory' => 'Operating', 'currentBalance' => 3000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
            ['id' => 18, 'code' => '6400', 'name' => 'Depreciation Expense', 'type' => 'expense', 'category' => 'expense', 'subCategory' => 'Non-Cash', 'currentBalance' => 3000, 'openingBalance' => 0, 'allowManualEntry' => true, 'isActive' => true, 'parentId' => null],
        ];
    }

    public function trialbalanceAndReportsIndex()
    {
        // Mock GL Accounts for Trial Balance & Reports
        // Needs Category and SubCategory for grouping
        $glAccounts = $this->getMockGlAccounts();
        return view('financialManagement.trialBalanceAndReports', compact('glAccounts'));
    }

    public function expenseManagementIndex()
    {
        return view('errors.under-development');
    }

    public function financialReportsIndex()
    {
        // Mock GL Accounts for Cash Flow
        $glAccounts = [
            // Cash
            ['code' => '1000', 'name' => 'Petty Cash', 'type' => 'asset', 'category' => 'current-asset', 'currentBalance' => 500, 'openingBalance' => 500],
            ['code' => '1010', 'name' => 'Checking Account', 'type' => 'asset', 'category' => 'current-asset', 'currentBalance' => 15000, 'openingBalance' => 12000],

            // AR
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset', 'category' => 'current-asset', 'currentBalance' => 8500, 'openingBalance' => 6000],

            // Inventory
            ['code' => '1400', 'name' => 'Raw Materials', 'type' => 'asset', 'category' => 'current-asset', 'currentBalance' => 12000, 'openingBalance' => 10000],
            ['code' => '1410', 'name' => 'Finished Goods', 'type' => 'asset', 'category' => 'current-asset', 'currentBalance' => 8000, 'openingBalance' => 7500],

            // Fixed Assets
            ['code' => '1600', 'name' => 'Machinery', 'type' => 'asset', 'category' => 'fixed-asset', 'currentBalance' => 55000, 'openingBalance' => 50000],
            ['code' => '1650', 'name' => 'Accumulated Depreciation', 'type' => 'asset', 'category' => 'fixed-asset', 'currentBalance' => -5000, 'openingBalance' => -2000],

            // AP
            ['code' => '2000', 'name' => 'Accounts Payable', 'type' => 'liability', 'category' => 'current-liability', 'currentBalance' => 6200, 'openingBalance' => 7000],
            ['code' => '2100', 'name' => 'Accrued Expenses', 'type' => 'liability', 'category' => 'current-liability', 'currentBalance' => 1200, 'openingBalance' => 1000],

            // Long Term
            ['code' => '2500', 'name' => 'Bank Loan', 'type' => 'liability', 'category' => 'long-term-liability', 'currentBalance' => 30000, 'openingBalance' => 35000],

            // Equity
            ['code' => '3000', 'name' => 'Owner Capital', 'type' => 'equity', 'category' => 'equity', 'currentBalance' => 45000, 'openingBalance' => 40000],

            // Revenue
            ['code' => '4000', 'name' => 'Sales Revenue', 'type' => 'revenue', 'category' => 'revenue', 'currentBalance' => 120000, 'openingBalance' => 0],

            // Expenses
            ['code' => '5000', 'name' => 'Cost of Goods Sold', 'type' => 'expense', 'category' => 'expense', 'currentBalance' => 65000, 'openingBalance' => 0],
            ['code' => '6000', 'name' => 'Rent Expense', 'type' => 'expense', 'category' => 'expense', 'currentBalance' => 12000, 'openingBalance' => 0],
            ['code' => '6100', 'name' => 'Salaries Expense', 'type' => 'expense', 'category' => 'expense', 'currentBalance' => 25000, 'openingBalance' => 0],
            ['code' => '6400', 'name' => 'Depreciation Expense', 'type' => 'expense', 'category' => 'expense', 'currentBalance' => 3000, 'openingBalance' => 0],
        ];

        // Mock Journal Entries for AR/AP Aging
        $journalEntries = [
            // AR Invoices (Debit 1200, Credit 4000)
            [
                'id' => 'JE-001',
                'entryDate' => date('Y-m-d', strtotime('-15 days')),
                'entryNumber' => 'INV-2024-001',
                'description' => 'Bakery Delight - Wholesale Order',
                'reference' => 'PO-998',
                'status' => 'posted',
                'type' => 'sales',
                'lines' => [
                    ['accountCode' => '1200', 'debit' => 1500, 'credit' => 0],
                    ['accountCode' => '4000', 'debit' => 0, 'credit' => 1500]
                ]
            ],
            [
                'id' => 'JE-002',
                'entryDate' => date('Y-m-d', strtotime('-45 days')),
                'entryNumber' => 'INV-2024-002',
                'description' => 'Coffee House - Monthly Supply',
                'reference' => 'PO-887',
                'status' => 'posted',
                'type' => 'sales',
                'lines' => [
                    ['accountCode' => '1200', 'debit' => 2200, 'credit' => 0],
                    ['accountCode' => '4000', 'debit' => 0, 'credit' => 2200]
                ]
            ],
            [
                'id' => 'JE-003',
                'entryDate' => date('Y-m-d', strtotime('-75 days')),
                'entryNumber' => 'INV-2023-156',
                'description' => 'Hotel Chain - Event Catering',
                'reference' => 'EVT-101',
                'status' => 'posted',
                'type' => 'sales',
                'lines' => [
                    ['accountCode' => '1200', 'debit' => 4800, 'credit' => 0],
                    ['accountCode' => '4000', 'debit' => 0, 'credit' => 4800]
                ]
            ],

            // AP Bills (Debit Exp/Asset, Credit 2000)
            [
                'id' => 'JE-004',
                'entryDate' => date('Y-m-d', strtotime('-20 days')),
                'entryNumber' => 'BILL-901',
                'description' => 'Flour Power - Raw Material',
                'reference' => 'INV-5564',
                'status' => 'posted',
                'type' => 'purchase',
                'lines' => [
                    ['accountCode' => '1400', 'debit' => 3200, 'credit' => 0],
                    ['accountCode' => '2000', 'debit' => 0, 'credit' => 3200]
                ]
            ],
            [
                'id' => 'JE-005',
                'entryDate' => date('Y-m-d', strtotime('-50 days')),
                'entryNumber' => 'BILL-882',
                'description' => 'Packaging Solutions - Boxes',
                'reference' => 'INV-112',
                'status' => 'posted',
                'type' => 'purchase',
                'lines' => [
                    ['accountCode' => '1410', 'debit' => 1500, 'credit' => 0],
                    ['accountCode' => '2000', 'debit' => 0, 'credit' => 1500]
                ]
            ],
            [
                'id' => 'JE-006',
                'entryDate' => date('Y-m-d', strtotime('-100 days')),
                'entryNumber' => 'BILL-700',
                'description' => 'Kitchen Equip - Maintenance',
                'reference' => 'SVC-99',
                'status' => 'posted',
                'type' => 'purchase',
                'lines' => [
                    ['accountCode' => '6000', 'debit' => 1500, 'credit' => 0], // Maintenance Exp
                    ['accountCode' => '2000', 'debit' => 0, 'credit' => 1500]
                ]
            ],
        ];

        return view('financialManagement.financialReports', compact('glAccounts', 'journalEntries'));
    }

    public function bankReconciliationIndex()
    {
        // Mock Data for Bank Reconciliation
        $glAccounts = [
            ['id' => '1010', 'code' => '1010', 'name' => 'Main Operating Account', 'type' => 'Asset', 'currentBalance' => 500000],
            ['id' => '1020', 'code' => '1020', 'name' => 'Payroll Account', 'type' => 'Asset', 'currentBalance' => 250000],
            ['id' => '1030', 'code' => '1030', 'name' => 'Petty Cash', 'type' => 'Asset', 'currentBalance' => 5000],
        ];

        $bankAccounts = [
            [
                'id' => 'BA-001',
                'accountName' => 'Main Operating Account',
                'accountNumber' => '1234567890',
                'bankName' => 'Commercial Bank',
                'glAccountId' => '1010',
                'glAccountCode' => '1010',
                'currentBalance' => 500000,
                'lastReconciledDate' => '2024-11-30',
                'lastReconciledBalance' => 480000,
                'isActive' => true,
                'createdAt' => '2024-01-01'
            ],
            [
                'id' => 'BA-002',
                'accountName' => 'Payroll Account',
                'accountNumber' => '0987654321',
                'bankName' => 'Peoples Bank',
                'glAccountId' => '1020',
                'glAccountCode' => '1020',
                'currentBalance' => 250000,
                'lastReconciledDate' => '2024-11-30',
                'lastReconciledBalance' => 240000,
                'isActive' => true,
                'createdAt' => '2024-01-01'
            ]
        ];

        $bankTransactions = [
            [
                'id' => 'BT-001',
                'bankAccountId' => 'BA-001',
                'transactionDate' => '2024-12-15',
                'description' => 'Customer Payment - ABC Cafe',
                'reference' => 'DEP-001',
                'type' => 'deposit',
                'amount' => 25000,
                'isReconciled' => true,
                'reconciledDate' => '2024-12-15',
                'createdAt' => '2024-12-15'
            ],
            [
                'id' => 'BT-002',
                'bankAccountId' => 'BA-001',
                'transactionDate' => '2024-12-20',
                'description' => 'Supplier Payment - Flour Mill',
                'reference' => 'CHK-1234',
                'type' => 'withdrawal',
                'amount' => 15000,
                'isReconciled' => false,
                'createdAt' => '2024-12-20'
            ],
            [
                'id' => 'BT-003',
                'bankAccountId' => 'BA-001',
                'transactionDate' => '2024-12-22',
                'description' => 'Bank Charges - December',
                'reference' => null,
                'type' => 'withdrawal',
                'amount' => 500,
                'isReconciled' => false,
                'createdAt' => '2024-12-22'
            ],
            [
                'id' => 'BT-004',
                'bankAccountId' => 'BA-001',
                'transactionDate' => '2024-12-25',
                'description' => 'Interest Income',
                'reference' => null,
                'type' => 'deposit',
                'amount' => 1200,
                'isReconciled' => false,
                'createdAt' => '2024-12-25'
            ]
        ];

        $reconciliations = [
            [
                'id' => 'BR-001',
                'bankAccountId' => 'BA-001',
                'reconDate' => '2024-11-30',
                'statementEndDate' => '2024-11-30',
                'statementBalance' => 480000,
                'glBalance' => 480000,
                'outstandingDeposits' => 0,
                'outstandingWithdrawals' => 0,
                'adjustments' => 0,
                'reconciledBalance' => 480000,
                'isBalanced' => true,
                'difference' => 0,
                'status' => 'completed',
                'completedBy' => 'Admin',
                'completedAt' => '2024-11-30 10:00:00',
                'createdAt' => '2024-11-30'
            ]
        ];

        return view('financialManagement.bankReconciliation', compact('bankAccounts', 'bankTransactions', 'reconciliations', 'glAccounts'));
    }

    public function inventoryGlMappingIndex()
    {
        // Mock Data for Inventory GL Mapping
        $categoryMappings = [
            [
                'id' => 'cat-map-1',
                'categoryName' => 'Raw Materials',
                'categoryType' => 'Material',
                'inventoryAccountId' => '12050',
                'cogsAccountId' => '50100',
                'isActive' => true,
                'notes' => 'Flour, sugar, eggs, etc.'
            ],
            [
                'id' => 'cat-map-2',
                'categoryName' => 'Finished Goods',
                'categoryType' => 'Product',
                'inventoryAccountId' => '12060',
                'cogsAccountId' => '50200',
                'isActive' => true,
                'notes' => 'Baked items ready for sale'
            ],
            [
                'id' => 'cat-map-3',
                'categoryName' => 'Packaging Materials',
                'categoryType' => 'Supply',
                'inventoryAccountId' => '12070',
                'cogsAccountId' => '50300',
                'isActive' => true,
                'notes' => 'Boxes, bags, wrappers'
            ],
            [
                'id' => 'cat-map-4',
                'categoryName' => 'Merchandise',
                'categoryType' => 'Product',
                'inventoryAccountId' => '12080',
                'cogsAccountId' => '50400',
                'isActive' => false,
                'notes' => 'Resale items'
            ]
        ];

        $supplierMappings = [
            [
                'id' => 'sup-map-1',
                'supplierTypeName' => 'Ingredient Suppliers',
                'apAccountId' => '20100',
                'defaultExpenseAccountId' => '50100',
                'isActive' => true,
                'notes' => 'Bulk ingredient vendors'
            ],
            [
                'id' => 'sup-map-2',
                'supplierTypeName' => 'Utility Providers',
                'apAccountId' => '20200',
                'defaultExpenseAccountId' => '60100', // Utilities Expense
                'isActive' => true,
                'notes' => 'Electricity, Water, Gas'
            ],
            [
                'id' => 'sup-map-3',
                'supplierTypeName' => 'Equipment Vendors',
                'apAccountId' => '20300',
                'defaultExpenseAccountId' => null, // No default, usually asset
                'isActive' => true,
                'notes' => 'Machinery and tools'
            ]
        ];

        $valuationConfig = [
            'method' => 'fifo',
            'methodName' => 'FIFO (First In, First Out)',
            'enableNRVAdjustments' => true,
            'enablePriceVarianceTracking' => false,
            'enableLandedCost' => false,
            'autoPostInventoryJEs' => true,
            'updatedAt' => '2025-01-10 09:30:00',
            'updatedBy' => 'System Admin'
        ];

        // GL Accounts Mock (Merged lists for simplicity in view, but structured for selection)
        $glAccounts = [
            ['id' => '12050', 'code' => '12050', 'name' => 'Raw Materials Inventory', 'type' => 'Asset', 'category' => 'Inventory'],
            ['id' => '12060', 'code' => '12060', 'name' => 'Finished Goods Inventory', 'type' => 'Asset', 'category' => 'Inventory'],
            ['id' => '12070', 'code' => '12070', 'name' => 'Packaging Inventory', 'type' => 'Asset', 'category' => 'Inventory'],
            ['id' => '12080', 'code' => '12080', 'name' => 'Merchandise Inventory', 'type' => 'Asset', 'category' => 'Inventory'],

            ['id' => '20100', 'code' => '20100', 'name' => 'Accounts Payable - Trade', 'type' => 'Liability', 'category' => 'Payables'],
            ['id' => '20200', 'code' => '20200', 'name' => 'Accounts Payable - Utilities', 'type' => 'Liability', 'category' => 'Payables'],
            ['id' => '20300', 'code' => '20300', 'name' => 'Accounts Payable - Equipment', 'type' => 'Liability', 'category' => 'Payables'],

            ['id' => '50100', 'code' => '50100', 'name' => 'COGS - Materials', 'type' => 'Expense', 'category' => 'COGS'],
            ['id' => '50200', 'code' => '50200', 'name' => 'COGS - Products', 'type' => 'Expense', 'category' => 'COGS'],
            ['id' => '50300', 'code' => '50300', 'name' => 'COGS - Packaging', 'type' => 'Expense', 'category' => 'COGS'],
            ['id' => '50400', 'code' => '50400', 'name' => 'COGS - Merchandise', 'type' => 'Expense', 'category' => 'COGS'],
            ['id' => '60100', 'code' => '60100', 'name' => 'Utilities Expense', 'type' => 'Expense', 'category' => 'Expense'],

        ];


        return view('financialManagement.inventoryGlMapping', compact('categoryMappings', 'supplierMappings', 'valuationConfig', 'glAccounts'));
    }
}
