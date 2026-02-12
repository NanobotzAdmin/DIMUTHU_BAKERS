<?php

use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdvancedPlannerController;
use App\Http\Controllers\AgentDistributionManagementController;
use App\Http\Controllers\AiAssistantManagementController;
use App\Http\Controllers\AnalyticsReportsManagementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerKioskConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DayEndProcessManagementController;
use App\Http\Controllers\DistributorAndSalesManagementController;
use App\Http\Controllers\FinancialManagementController;
use App\Http\Controllers\InterfaceManagementController;
use App\Http\Controllers\InventoryManagementController;
use App\Http\Controllers\OnlineOrderingManagementController;
use App\Http\Controllers\OverheadManagementController;
use App\Http\Controllers\PosManagementController;
use App\Http\Controllers\PrivilegeManagementController;
use App\Http\Controllers\ProductionManagementController;
use App\Http\Controllers\ProductManagementController;
use App\Http\Controllers\SupplierManagementController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WasteRecoveryManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission', 'ensure.branch'])->group(function () {
    Route::get('/adminDashboard', [DashboardController::class, 'adminDashboard'])->name('adminDashboard');

    // User Management
    Route::get('/user-management', [UserManagementController::class, 'userManageIndex'])->name('userManagement.index');
    Route::post('/user-management/store', [UserManagementController::class, 'store'])->name('userManagement.store');
    Route::get('/user-management/{id}/edit', [UserManagementController::class, 'edit'])->name('userManagement.edit');
    Route::post('/user-management/{id}/update', [UserManagementController::class, 'update'])->name('userManagement.update');
    Route::post('/user-management/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('userManagement.toggleStatus');
    Route::get('/user-configuration', [UserManagementController::class, 'configurationIndex'])->name('userConfiguration.index');
    Route::get('/user-management/{id}/assignments', [UserManagementController::class, 'getAssignments'])->name('userManagement.assignments.get');
    Route::post('/user-management/{id}/assignments', [UserManagementController::class, 'updateAssignments'])->name('userManagement.assignments.update');
    Route::get('/user-roles/fetch', [UserManagementController::class, 'fetchUserRoles'])->name('userRoles.fetch');
    Route::post('/user-roles/store', [UserManagementController::class, 'storeUserRole'])->name('userRoles.store');
    Route::post('/user-roles/update', [UserManagementController::class, 'updateUserRole'])->name('userRoles.update');
    Route::delete('/user-roles/delete', [UserManagementController::class, 'deleteUserRole'])->name('userRoles.delete');

    // Production Management
    Route::get('/production-management', [ProductionManagementController::class, 'productionManageIndex'])->name('productionManagement.index');
    Route::get('/recipe-management', [ProductionManagementController::class, 'recipeManageIndex'])->name('recipeManagement.index');
    Route::get('/recipe-management/search-product-items', [ProductionManagementController::class, 'searchProductItems'])->name('recipeManagement.searchProductItems');
    Route::post('/recipe-management', [ProductionManagementController::class, 'storeRecipe'])->name('recipeManagement.store');
    Route::get('/recipe-management/{id}', [ProductionManagementController::class, 'getRecipe'])->name('recipeManagement.show');
    Route::get('/production-scheduling', [ProductionManagementController::class, 'productionSchedulingIndex'])->name('productionScheduling.index');
    Route::get('/kitchen-production', [ProductionManagementController::class, 'kitchenProductionIndex'])->name('kitchenProduction.index');
    Route::get('/production-execution', [ProductionManagementController::class, 'productionExecutionIndex'])->name('productionExecution.index');
    Route::get('/wastage-tracking', [ProductionManagementController::class, 'wastageTrackingIndex'])->name('wastageTracking.index');
    Route::get('/batch-tracking', [ProductionManagementController::class, 'batchTrackingIndex'])->name('batchTracking.index');

    // Inventory Management
    Route::get('/inventory-management', [InventoryManagementController::class, 'inventoryManageIndex'])->name('inventoryManagement.index');
    Route::get('/kitchen-inventory', [InventoryManagementController::class, 'kitchenInventoryIndex'])->name('kitchenInventory.index');
    Route::get('/purchase-order-management', [InventoryManagementController::class, 'purchaseOrderManageIndex'])->name('purchaseOrderManage.index');
    Route::get('/inventory-dashboard', [InventoryManagementController::class, 'inventoryDashboardIndex'])->name('inventoryDashboard.index');
    Route::get('/inventory-master', [InventoryManagementController::class, 'inventoryMasterIndex'])->name('inventoryMaster.index');
    Route::get('/inventory-reports-analysis', [InventoryManagementController::class, 'inventoryReportsAnalysisIndex'])->name('inventoryReportsAnalysis.index');
    Route::get('/stock-adjustments', [InventoryManagementController::class, 'stockAdjustmentsIndex'])->name('stockAdjustments.index');
    Route::get('/supplier-compare', [InventoryManagementController::class, 'supplierCompareIndex'])->name('supplierCompare.index');
    Route::get('/create-purchase-order', [InventoryManagementController::class, 'createPurchaseOrderIndex'])->name('createPurchaseOrder.index');
    Route::post('/create-purchase-order/store', [InventoryManagementController::class, 'storePurchaseOrder'])->name('createPurchaseOrder.store');
    Route::post('/purchase-order/approve/{id}', [InventoryManagementController::class, 'approvePurchaseOrder'])->name('purchaseOrder.approve');
    Route::post('/purchase-order/send/{id}', [InventoryManagementController::class, 'sendToSupplier'])->name('purchaseOrder.send');
    Route::get('/purchase-order/{id}/pdf', [InventoryManagementController::class, 'downloadPdf'])->name('purchaseOrder.downloadPdf');
    Route::post('/create-grn/prepare', [InventoryManagementController::class, 'prepareCreateGRN'])->name('createGRN.prepare');
    Route::get('/create-grn', [InventoryManagementController::class, 'createGRNIndex'])->name('createGRN.index');
    Route::post('/create-grn/store', [InventoryManagementController::class, 'storeGRN'])->name('createGRN.store');
    Route::get('/inventory/grn-management', [InventoryManagementController::class, 'grnIndex'])->name('grnManagement.index');
    Route::get('/cake-section', [InventoryManagementController::class, 'cakeSectionIndex'])->name('cakeSection.index');
    Route::get('/bakery-section', [InventoryManagementController::class, 'bakerySectionIndex'])->name('bakerySection.index');
    Route::get('/manage-stock-transfers', [InventoryManagementController::class, 'manageStockTransfers'])->name('manageStockTransfers.index');
    Route::get('/create-stock-transfer', [InventoryManagementController::class, 'createStockTransferIndex'])->name('createStockTransfer.index');
    Route::post('/api/inventory/transfer/stock', [InventoryManagementController::class, 'getTransferableStock'])->name('inventory.transfer.stock');
    Route::post('/api/inventory/transfer/store', [InventoryManagementController::class, 'storeTransfer'])->name('inventory.transfer.store');
    Route::post('/api/inventory/transfer/update-status', [InventoryManagementController::class, 'updateTransferStatus'])->name('inventory.transfer.update-status');
    Route::get('/section-outlet-inventory', [InventoryManagementController::class, 'sectionOutletInventoryIndex'])->name('sectionOutletInventory.index');
    Route::post('/api/inventory/department-stock', [InventoryManagementController::class, 'getDepartmentStock'])->name('inventory.department.stock');
    Route::get('/warehouse-management', [InventoryManagementController::class, 'warehouseManagementIndex'])->name('warehouseManagement.index');

    // Distributor and Sales Management
    Route::get('/sales-overview', [DistributorAndSalesManagementController::class, 'salesOverviewIndex'])->name('salesOverview.index');
    Route::get('/customer-management', [DistributorAndSalesManagementController::class, 'customerManageIndex'])->name('customerManagement.index');
    Route::get('/quotation-management', [DistributorAndSalesManagementController::class, 'quotationManageIndex'])->name('quotations.index');
    Route::post('/api/quotation-management/store', [DistributorAndSalesManagementController::class, 'quotationManageStore'])->name('quotationManagement.store');
    Route::post('/api/quotation-management/save-settings', [DistributorAndSalesManagementController::class, 'saveQuotationSettings'])->name('quotationManagement.saveSettings');
    Route::get('/api/quotation-management/get-settings', [DistributorAndSalesManagementController::class, 'getQuotationSettings'])->name('quotationManagement.getSettings');
    Route::get('/api/quotation-management/download-pdf/{id}', [DistributorAndSalesManagementController::class, 'downloadQuotationPdf'])->name('quotationManagement.downloadPdf');
    Route::get('/order-management', [DistributorAndSalesManagementController::class, 'orderManageIndex'])->name('order-management.index');

    // Order Management API Routes
    Route::post('/api/order-management/search-products', [DistributorAndSalesManagementController::class, 'orderManageSearchProducts'])->name('orderManagement.searchProducts');
    Route::post('/api/order-management/search-customers', [DistributorAndSalesManagementController::class, 'orderManageSearchCustomers'])->name('orderManagement.searchCustomers');
    Route::post('/api/order-management/search-quotations', [DistributorAndSalesManagementController::class, 'orderManageSearchQuotations'])->name('orderManagement.searchQuotations');
    Route::post('/api/order-management/create-customer', [DistributorAndSalesManagementController::class, 'orderManageCreateCustomer'])->name('orderManagement.createCustomer');
    Route::post('/api/order-management/store', [DistributorAndSalesManagementController::class, 'orderManageStore'])->name('orderManagement.store');
    Route::post('/api/order-management/update-status', [DistributorAndSalesManagementController::class, 'orderManageUpdateStatus'])->name('orderManagement.updateStatus');
    Route::post('/api/order-management/dispatch-order', [DistributorAndSalesManagementController::class, 'dispatchOrder'])->name('orderManagement.dispatchOrder');
    Route::post('/api/order-management/approve-dispatch', [DistributorAndSalesManagementController::class, 'approveDispatch'])->name('orderManagement.approveDispatch');

    Route::get('/invoice-management', [DistributorAndSalesManagementController::class, 'invoiceManageIndex'])->name('invoice-management.index');
    Route::get('/payment-tracking', [DistributorAndSalesManagementController::class, 'paymentTrackingIndex'])->name('payment-tracking.index');
    Route::get('/delivery-scheduling', [DistributorAndSalesManagementController::class, 'deliverySchedulingIndex'])->name('delivery-scheduling.index');

    // Agent Distribution System
    Route::get('/agent-distribution-dashboard', [AgentDistributionManagementController::class, 'agentDistributionSystemIndex'])->name('agentDistributionSystem.index');
    Route::get('/agent-management', [AgentDistributionManagementController::class, 'agentManageIndex'])->name('agentManagement.index');
    Route::get('/route-management', [AgentDistributionManagementController::class, 'routeManageIndex'])->name('routeManagement.index');
    Route::get('/route-management/{id}/builder', [AgentDistributionManagementController::class, 'routeBuilderView'])->name('routeBuilder.view');
    Route::get('/daily-loads', [AgentDistributionManagementController::class, 'dailyLoadsIndex'])->name('dailyLoads.index');
    Route::get('/distributor-customer-management', [AgentDistributionManagementController::class, 'distributorCustomerManageIndex'])->name('distributorCustomerManagement.index');
    Route::get('/distributor-customer-management/{id}', [App\Http\Controllers\AgentDistributionManagementController::class, 'distributorCustomerDetail'])->name('distributor.customer.detail');
    Route::get('/settlement-list', [AgentDistributionManagementController::class, 'settlementListIndex'])->name('settlementList.index');
    Route::get('/agent-distribution/settlements/{id}', [AgentDistributionManagementController::class, 'settlementDetail'])->name('settlementDetail.index');
    Route::get('/gl-posting', [AgentDistributionManagementController::class, 'glPostingIndex'])->name('glPosting.index');
    Route::get('/commission-overview', [AgentDistributionManagementController::class, 'commissionOverviewIndex'])->name('commissionOverview.index');
    Route::get('/commission-payment', [AgentDistributionManagementController::class, 'commissionPaymentIndex'])->name('commissionPayment.index');
    Route::get('/commission-statements', [AgentDistributionManagementController::class, 'commissionStatementsIndex'])->name('commissionStatements.index');
    Route::get('/agent-analytics', [AgentDistributionManagementController::class, 'agentAnalyticsIndex'])->name('agentAnalytics.index');
    Route::get('/financial-dashboard', [AgentDistributionManagementController::class, 'financialDashboardIndex'])->name('financialDashboard.index');
    Route::get('/agent-distribution-report', [AgentDistributionManagementController::class, 'agentDistributionReportIndex'])->name('agentDistributionReport.index');
    Route::get('/sattlement-automation', [AgentDistributionManagementController::class, 'sattlementAutomationIndex'])->name('sattlementAutomation.index');
    Route::get('/incentives-and-bonuses', [AgentDistributionManagementController::class, 'incentivesAndBonusesIndex'])->name('incentivesAndBonuses.index');
    Route::get('/dispute-resolution', [AgentDistributionManagementController::class, 'disputeResolutionIndex'])->name('disputeResolution.index');

    // Agent CRUD API Routes
    Route::post('/api/agents/create', [AgentDistributionManagementController::class, 'createAgent'])->name('agents.create');
    Route::get('/api/agents/{id}', [AgentDistributionManagementController::class, 'loadAgentDetails'])->name('agents.details');
    Route::put('/api/agents/{id}/update', [AgentDistributionManagementController::class, 'updateAgent'])->name('agents.update');
    Route::delete('/api/agents/{id}/deactivate', [AgentDistributionManagementController::class, 'deactivateAgent'])->name('agents.deactivate');

    // Route CRUD API Routes
    Route::post('/api/routes/create', [AgentDistributionManagementController::class, 'createRoute'])->name('routes.create');
    Route::get('/api/routes/{id}', [AgentDistributionManagementController::class, 'loadRouteDetails'])->name('routes.details');
    Route::put('/api/routes/{id}/update', [AgentDistributionManagementController::class, 'updateRoute'])->name('routes.update');
    Route::delete('/api/routes/{id}/deactivate', [AgentDistributionManagementController::class, 'deactivateRoute'])->name('routes.deactivate');
    Route::post('/api/routes/{id}/customers', [AgentDistributionManagementController::class, 'saveRouteCustomers'])->name('routes.customers.save');
    Route::post('/api/routes/save-builder', [AgentDistributionManagementController::class, 'saveBuilderRoute'])->name('routes.save.builder');

    // Customer CRUD API Routes
    Route::post('/api/customers/create', [AgentDistributionManagementController::class, 'createCustomer'])->name('customers.create');
    Route::put('/api/customers/{id}/update', [AgentDistributionManagementController::class, 'updateCustomer'])->name('customers.update');
    Route::delete('/api/customers/{id}/delete', [AgentDistributionManagementController::class, 'deleteCustomer'])->name('customers.delete');

    // Daily Loads API Routes
    Route::post('/api/daily-loads/search-products', [AgentDistributionManagementController::class, 'searchProductsForLoad'])->name('agentDistribution.searchProductsForLoad');
    Route::post('/api/daily-loads/get-price', [AgentDistributionManagementController::class, 'getProductPrice'])->name('agentDistribution.getProductPrice');
    Route::post('/api/daily-loads/store', [AgentDistributionManagementController::class, 'storeDailyLoad'])->name('agentDistribution.storeDailyLoad');
    Route::post('/api/daily-loads/mark-as-loaded', [AgentDistributionManagementController::class, 'markAsLoaded'])->name('agentDistribution.markAsLoaded');

    // POS Management
    Route::get('/pos', [PosManagementController::class, 'posIndex'])->name('pos.index');
    Route::get('/pos/data', [PosManagementController::class, 'getPosData'])->name('pos.data');
    Route::post('/pos/customers/search', [PosManagementController::class, 'searchCustomers'])->name('pos.customers.search');
    Route::post('/pos/customers/store', [PosManagementController::class, 'storeCustomer'])->name('pos.customers.store');
    Route::post('/pos/store-sale', [PosManagementController::class, 'storeSale'])->name('pos.store');
    Route::get('/pos/receipt/{id}', [PosManagementController::class, 'showReceipt'])->name('pos.receipt');

    // POS Tab Partials & Data
    Route::get('/pos/tabs/{view}', [PosManagementController::class, 'getTabPartial'])->name('pos.tabs');
    Route::get('/pos/pickup', [PosManagementController::class, 'getPickupOrders'])->name('pos.pickup');
    Route::get('/pos/orders', [PosManagementController::class, 'getIncomingOrders'])->name('pos.orders');
    Route::get('/pos/history', [PosManagementController::class, 'getTransactionHistory'])->name('pos.history');
    Route::get('/pos/returns', [PosManagementController::class, 'getReturns'])->name('pos.returns');
    Route::get('/pos/recon', [PosManagementController::class, 'getReconciliationData'])->name('pos.recon');
    Route::get('/pos/report', [PosManagementController::class, 'getShiftReportData'])->name('pos.report');

    // Product Management
    Route::get('/product-registration', [ProductManagementController::class, 'productRegistrationIndex'])->name('productRegistration.index');
    Route::post('/product/store', [ProductManagementController::class, 'storeProduct'])->name('product.store');
    Route::get('/product/search', [ProductManagementController::class, 'searchProducts'])->name('product.search');
    Route::get('/product/items', [ProductManagementController::class, 'fetchProductItems'])->name('product.items.fetch');
    Route::post('/product/status', [ProductManagementController::class, 'updateProductStatus'])->name('product.status.update');
    Route::post('/product/update-item-types', [ProductManagementController::class, 'updateProductItemTypes'])->name('product.item.types.update');
    Route::get('/product-management', [ProductManagementController::class, 'productManageIndex'])->name('productManagement.index');
    Route::get('/product-configuration', [ProductManagementController::class, 'configurationIndex'])->name('productConfiguration.index');

    // Customer Kiosk Configuration
    Route::get('/customer-kiosk-configuration', [CustomerKioskConfigurationController::class, 'customerKioskConfigurationIndex'])->name('customerKioskConfiguration.index');

    // Online Ordering Dashboard
    Route::get('/online-ordering-dashboard', [OnlineOrderingManagementController::class, 'onlineOrderingDashboardIndex'])->name('onlineOrderingDashboard.index');
    Route::get('/online-order-management', [OnlineOrderingManagementController::class, 'onlineOrderManagementIndex'])->name('onlineOrderManagement.index');
    Route::get('/online-order-settings', [OnlineOrderingManagementController::class, 'onlineOrderSettingsIndex'])->name('onlineOrderSettings.index');

    // Financial Management
    Route::get('/financial-management-overview', [FinancialManagementController::class, 'financialManagementOverviewIndex'])->name('financialManagementOverview.index');
    Route::get('/chart-of-accounts', [FinancialManagementController::class, 'chartOfAccountsIndex'])->name('chartOfAccounts.index');
    Route::get('/journal-entries', [FinancialManagementController::class, 'journalEntriesIndex'])->name('journalEntries.index');
    Route::get('/trialbalance-and-reports', [FinancialManagementController::class, 'trialbalanceAndReportsIndex'])->name('trialbalanceAndReports.index');
    Route::get('/expense-management', [FinancialManagementController::class, 'expenseManagementIndex'])->name('expenseManagement.index');
    Route::get('/financial-reports', [FinancialManagementController::class, 'financialReportsIndex'])->name('financialReports.index');
    Route::get('/bank-reconciliation', [FinancialManagementController::class, 'bankReconciliationIndex'])->name('bankReconciliation.index');
    Route::get('/inventory-gl-mapping', [FinancialManagementController::class, 'inventoryGlMappingIndex'])->name('inventoryGlMapping.index');

    // Overhead Management
    Route::get('/overhead-management-dashboard', [OverheadManagementController::class, 'overheadManageIndex'])->name('overheadManagementDashboard.index');
    Route::get('/expense-recording', [OverheadManagementController::class, 'expenseRecordingIndex'])->name('expenseRecording.index');
    Route::get('/cost-pools', [OverheadManagementController::class, 'costPoolsIndex'])->name('costPools.index');
    Route::get('/activity-based-costing', [OverheadManagementController::class, 'activityBasedCostingIndex'])->name('activityBasedCosting.index');
    Route::get('/allocation-wizard', [OverheadManagementController::class, 'allocationWizardIndex'])->name('allocationWizard.index');
    Route::get('/allocation-posting', [OverheadManagementController::class, 'allocationPostingIndex'])->name('allocationPosting.index');
    Route::get('/variance-reconciliation', [OverheadManagementController::class, 'varianceReconciliationIndex'])->name('varianceReconciliation.index');
    Route::get('/allocation-history', [OverheadManagementController::class, 'allocationHistoryIndex'])->name('allocationHistory.index');
    Route::get('/analytics-dashboard', [OverheadManagementController::class, 'analyticsDashboardIndex'])->name('analyticsDashboard.index');
    Route::get('/cost-allocation-report', [OverheadManagementController::class, 'costAllocationReportIndex'])->name('costAllocationReport.index');
    Route::get('/product-costing-analysis', [OverheadManagementController::class, 'productCostingAnalysisIndex'])->name('productCostingAnalysis.index');
    Route::get('/budget-planning', [OverheadManagementController::class, 'budgetPlanningIndex'])->name('budgetPlanning.index');
    Route::get('/variance-analysis', [OverheadManagementController::class, 'varianceAnalysisIndex'])->name('varianceAnalysis.index');
    Route::get('/budget-forecasting', [OverheadManagementController::class, 'budgetForecastingIndex'])->name('budgetForecasting.index');
    Route::get('/gl-account-mapping', [OverheadManagementController::class, 'glAccountMappingIndex'])->name('glAccountMapping.index');

    // Waste Recovery
    Route::get('/waste-recovery-configuration', [WasteRecoveryManagementController::class, 'wasteRecoveryConfigurationIndex'])->name('wasteRecoveryConfiguration.index');
    Route::get('/waste-tracking', [WasteRecoveryManagementController::class, 'wasteTrackingIndex'])->name('wasteTracking.index');
    Route::get('/waste-recovery-dashboard', [WasteRecoveryManagementController::class, 'wasteRecoveryDashboardIndex'])->name('wasteRecoveryDashboard.index');
    Route::get('/waste-recovery-reports', [WasteRecoveryManagementController::class, 'wasteRecoveryReportsIndex'])->name('wasteRecoveryReports.index');
    Route::get('/waste-recovery-automation', [WasteRecoveryManagementController::class, 'wasteRecoveryAutomationIndex'])->name('wasteRecoveryAutomation.index');

    // AI Assistant
    Route::get('/ai-assistant', [AiAssistantManagementController::class, 'aiAssistantIndex'])->name('aiAssistant.index');

    // Day end Process
    Route::get('/day-end-process', [DayEndProcessManagementController::class, 'dayEndProcessIndex'])->name('dayEndProcess.index');

    // Analytics & Reports
    Route::get('/analytics-reports-dashboard', [AnalyticsReportsManagementController::class, 'analyticsReportsDashboardIndex'])->name('analyticsReportsDashboard.index');
    Route::get('/analytics-reports-sales-analytics', [AnalyticsReportsManagementController::class, 'analyticsReportsSalesAnalyticsIndex'])->name('analyticsReportsSalesAnalytics.index');
    Route::get('/analytics-reports-inventory-reports', [AnalyticsReportsManagementController::class, 'analyticsReportsInventoryReportsIndex'])->name('analyticsReportsInventoryReports.index');
    Route::get('/analytics-reports-production-reports', [AnalyticsReportsManagementController::class, 'analyticsReportsProductionReportsIndex'])->name('analyticsReportsProductionReports.index');
    Route::get('/analytics-reports-financial-reports', [AnalyticsReportsManagementController::class, 'analyticsReportsFinancialReportsIndex'])->name('analyticsReportsFinancialReports.index');

    // Product Types
    Route::get('/product-types/fetch', [ProductManagementController::class, 'fetchProductTypes'])->name('productTypes.fetch');
    Route::post('/product-types/store', [ProductManagementController::class, 'storeProductType'])->name('productTypes.store');
    Route::post('/product-types/update', [ProductManagementController::class, 'updateProductType'])->name('productTypes.update');
    Route::delete('/product-types/delete', [ProductManagementController::class, 'deleteProductType'])->name('productTypes.delete');

    // Brands
    Route::get('/brands/fetch', [ProductManagementController::class, 'fetchBrands'])->name('brands.fetch');
    Route::post('/brands/store', [ProductManagementController::class, 'storeBrand'])->name('brands.store');
    Route::post('/brands/update', [ProductManagementController::class, 'updateBrand'])->name('brands.update');
    Route::delete('/brands/delete', [ProductManagementController::class, 'deleteBrand'])->name('brands.delete');

    // Variations
    Route::get('/variations/fetch', [ProductManagementController::class, 'fetchVariations'])->name('variations.fetch');
    Route::post('/variations/store', [ProductManagementController::class, 'storeVariation'])->name('variations.store');
    Route::post('/variations/update', [ProductManagementController::class, 'updateVariation'])->name('variations.update');
    Route::delete('/variations/delete', [ProductManagementController::class, 'deleteVariation'])->name('variations.delete');

    // Variation Values
    Route::get('/variation-values/fetch', [ProductManagementController::class, 'fetchVariationValues'])->name('variationValues.fetch');
    Route::post('/variation-values/store', [ProductManagementController::class, 'storeVariationValue'])->name('variationValues.store');
    Route::post('/variation-values/update', [ProductManagementController::class, 'updateVariationValue'])->name('variationValues.update');
    Route::delete('/variation-values/delete', [ProductManagementController::class, 'deleteVariationValue'])->name('variationValues.delete');

    // Supplier Management
    Route::get('/supplier-management', [SupplierManagementController::class, 'supplierManageIndex'])->name('supplierManagement.index');
    Route::post('/supplier-management/store', [SupplierManagementController::class, 'createSupplier'])->name('supplier.store');
    Route::post('/supplier-management/update', [SupplierManagementController::class, 'updateSupplier'])->name('supplier.update');
    Route::get('/product-items/search', [SupplierManagementController::class, 'searchProductItems'])->name('product.items.search');

    // Interface Management
    Route::get('/interface-management', [InterfaceManagementController::class, 'index'])->name('interfaceManagement.index');
    Route::post('/interface-management/store-topic', [InterfaceManagementController::class, 'storeTopic'])->name('interfaceManagement.storeTopic');
    Route::post('/interface-management/update-topic', [InterfaceManagementController::class, 'updateTopic'])->name('interfaceManagement.updateTopic');
    Route::post('/interface-management/store-interface', [InterfaceManagementController::class, 'storeInterface'])->name('interfaceManagement.storeInterface');
    Route::post('/interface-management/update-interface', [InterfaceManagementController::class, 'updateInterface'])->name('interfaceManagement.updateInterface');
    Route::post('/interface-management/store-component', [InterfaceManagementController::class, 'storeComponent'])->name('interfaceManagement.storeComponent');
    Route::post('/interface-management/update-component', [InterfaceManagementController::class, 'updateComponent'])->name('interfaceManagement.updateComponent');
    Route::post('/interface-management/save-interface-order', [InterfaceManagementController::class, 'saveInterfaceOrder'])->name('interfaceManagement.saveInterfaceOrder');
    Route::post('/interface-management/save-topic-order', [InterfaceManagementController::class, 'saveTopicOrder'])->name('interfaceManagement.saveTopicOrder');

    // Privilege Management
    Route::get('/privilege-management', [PrivilegeManagementController::class, 'index'])->name('privilegeManagement.index');
    Route::post('/privilege-management/get-privileges', [PrivilegeManagementController::class, 'getPrivileges'])->name('privilegeManagement.getPrivileges');
    Route::post('/privilege-management/update-privilege', [PrivilegeManagementController::class, 'updatePrivilege'])->name('privilegeManagement.updatePrivilege');

    // Admin Settings
    Route::get('/adminSettings', [AdminSettingsController::class, 'index'])->name('adminSettings.index');
    Route::get('/adminSettings/branches/fetch', [AdminSettingsController::class, 'fetchBranches'])->name('adminSettings.branches.fetch');
    Route::get('/adminSettings/branch-types/fetch', [AdminSettingsController::class, 'fetchBranchTypes'])->name('adminSettings.branchTypes.fetch');
    Route::post('/adminSettings/branch-types/store', [AdminSettingsController::class, 'storeBranchType'])->name('adminSettings.branchTypes.store');
    Route::get('/adminSettings/departments/fetch', [AdminSettingsController::class, 'fetchDepartments'])->name('adminSettings.departments.fetch');
    Route::post('/adminSettings/branches/assign-departments', [AdminSettingsController::class, 'assignDepartments'])->name('adminSettings.branches.assignDepartments');
    Route::post('/adminSettings/branches/store', [AdminSettingsController::class, 'storeBranch'])->name('adminSettings.branches.store');
    Route::post('/adminSettings/branches/setDefault', [AdminSettingsController::class, 'setDefaultRaw'])->name('adminSettings.branches.setDefault');
    Route::post('/adminSettings/branches/toggleStatus', [AdminSettingsController::class, 'toggleStatus'])->name('adminSettings.branches.toggleStatus');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Advanced Planner
    Route::get('/advanced-planner', [AdvancedPlannerController::class, 'index'])->name('advancedPlanner.index');
    Route::post('/production/save-schedule', [AdvancedPlannerController::class, 'store'])->name('advancedPlanner.store');
    Route::post('/production/update-schedule', [AdvancedPlannerController::class, 'update'])->name('advancedPlanner.update');
    Route::post('/production/delete-schedule', [AdvancedPlannerController::class, 'destroy'])->name('advancedPlanner.destroy');
    Route::post('/api/resources/store', [AdvancedPlannerController::class, 'storeResource'])->name('advancedPlanner.storeResource');
    Route::post('/api/departments/store', [AdvancedPlannerController::class, 'storeDepartment'])->name('advancedPlanner.storeDepartment');
    Route::get('/api/production/branch-departments', [AdvancedPlannerController::class, 'fetchBranchDepartments'])->name('advancedPlanner.fetchBranchDepartments');
    Route::get('/api/production/resources', [AdvancedPlannerController::class, 'fetchResources'])->name('advancedPlanner.fetchResources');
    Route::get('/production/timeline', [AdvancedPlannerController::class, 'fetchTimelineData'])->name('advancedPlanner.fetchTimelineData');
    Route::get('/api/production/search-resources', [AdvancedPlannerController::class, 'searchResources'])->name('advancedPlanner.searchResources');
    Route::post('/api/production/store-resource', [AdvancedPlannerController::class, 'storeResource'])->name('advancedPlanner.storeResourceLogic');
    Route::get('/production/order-recipe-details/{id}', [AdvancedPlannerController::class, 'getOrderRecipeDetails'])->name('advancedPlanner.orderRecipeDetails');
    Route::post('/production/start-batch', [ProductionManagementController::class, 'startBatch'])->name('production.startBatch');
    Route::post('/production/complete-step', [ProductionManagementController::class, 'completeStep'])->name('production.completeStep');
    Route::post('/production/complete-batch', [ProductionManagementController::class, 'completeBatch'])->name('production.completeBatch');
});

// Branch Selection
Route::middleware(['auth'])->group(function () {
    Route::get('/select-branch', [AuthController::class, 'selectBranchIndex'])->name('selectBranch.index');
    Route::post('/select-branch', [AuthController::class, 'selectBranchStore'])->name('selectBranch.store');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'loginIndex'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::get('/test-qty', function () {
    try {
        // Create dummy product data
        DB::beginTransaction();

        $product = \App\Models\PmProduct::create([
            'product_name' => 'Test Product',
            'status' => 1,
            'created_by' => 1
        ]);

        $variation = \App\Models\PmVariation::create([
            'variation_name' => 'Test Variation',
            'status' => 1,
            'created_by' => 1
        ]);

        // KG variation (Unit ID 3 -> 1000g)
        $varValueKg = \App\Models\PmVariationValue::create([
            'pm_variation_id' => $variation->id,
            'unit_of_measurement_id' => 3, // kg
            'variation_value' => '50', // 50kg
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        $itemKg = \App\Models\PmProductItem::create([
            'pm_product_id' => $product->id,
            'pm_variation_value_id' => $varValueKg->id,
            'product_name' => '50kg Test Bag',
            'status' => 1,
            'created_by' => 1,
            'updated_by' => 1
        ]);

        // Test StmStock creation
        $stock = \App\Models\StmStock::create([
            'pm_product_item_id' => $itemKg->id,
            'quantity' => 2, // 2 units of 50kg
            'created_by' => 1,
            'updated_by' => 1
        ]);

        DB::rollBack();

        return response()->json([
            'qty' => $stock->quantity,
            'qty_in_unit' => $stock->qty_in_unit,
            'expected' => 100000.00,
            'pass' => floatval($stock->qty_in_unit) == 100000.00
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()]);
    }
});


