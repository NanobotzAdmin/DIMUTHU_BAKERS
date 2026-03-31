<?php

use App\Http\Controllers\ApiCustomerController;
use App\Http\Controllers\ApiGRNController;
use App\Http\Controllers\ApiInventoryController;
use App\Http\Controllers\ApiManagementController;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\DistributorAndSalesManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('guest')->group(function () {
    Route::post('/login', [ApiUserController::class , 'login']);

});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiUserController::class , 'logout']);

    Route::post('/create-customer', [ApiCustomerController::class , 'createCustomer']);
    Route::get('/search-master-customers', [ApiCustomerController::class , 'searchMasterCustomers']);

    // Management Routes
    Route::get('/drivers', [ApiManagementController::class , 'getDrivers']);
    Route::post('/create-driver', [ApiManagementController::class , 'createDriver']);
    Route::post('/update-driver/{id}', [ApiManagementController::class , 'updateDriver']);

    Route::get('/supervisors', [ApiManagementController::class , 'getSupervisors']);
    Route::post('/create-supervisor', [ApiManagementController::class , 'createSupervisor']);
    Route::post('/update-supervisor/{id}', [ApiManagementController::class , 'updateSupervisor']);

    Route::get('/vehicles', [ApiManagementController::class , 'getVehicles']);
    Route::post('/create-vehicle', [ApiManagementController::class , 'createVehicle']);
    Route::post('/update-vehicle/{id}', [ApiManagementController::class , 'updateVehicle']);


    // Route Management
    Route::get('/routes', [ApiManagementController::class , 'getRoutes']);
    Route::post('/route-detail/{id}', [ApiManagementController::class , 'getRoute']);
    Route::post('/create-route', [ApiManagementController::class , 'createRoute']);
    Route::post('/update-route/{id}', [ApiManagementController::class , 'updateRoute']);
    Route::post('/assign-route/{id}', [ApiManagementController::class , 'assignRouteResources']);


    // Customer Management
    Route::get('/customers', [ApiManagementController::class , 'getCustomers']);
    Route::post('/route-customers/{id}', [ApiManagementController::class , 'syncRouteCustomers']);


    // Daily Load Management
    Route::get('/daily-loads', [ApiManagementController::class , 'getDailyLoads']);
    Route::post('/daily-load-detail/{id}', [ApiManagementController::class , 'getDailyLoad']);
    Route::post('/create-daily-load', [ApiManagementController::class , 'createDailyLoad']);
    Route::post('/update-daily-load/{id}', [ApiManagementController::class , 'updateDailyLoad']);
    Route::post('/finish-daily-load/{id}', [ApiManagementController::class , 'finishDailyLoad']);
    Route::post('/daily-load-items/{id}', [ApiManagementController::class , 'addDailyLoadItems']);
    Route::get('/product-items', [ApiManagementController::class , 'getProductItems']);

    // Order Request Routes
    Route::get('/order-request', [ApiGRNController::class , 'index']);
    Route::get('/order-request/products', [ApiGRNController::class , 'getProducts']);
    Route::post('/order-request/create', [ApiGRNController::class , 'createOrderRequest']);
    Route::post('/order-request/{id}/payment', [ApiGRNController::class , 'addPayment']);
    Route::get('/order-request/{id}', [ApiGRNController::class , 'show']);
    Route::post('/order-request/{id}/confirm', [ApiGRNController::class , 'confirmOrder']);

    // Order Management Workflow Routes - Agents only handle completion via mobile
    Route::post('/order-request/complete', [DistributorAndSalesManagementController::class , 'completeOrder']);

    // Agent Inventory
    Route::get('/agent-stock', [ApiInventoryController::class , 'getAgentStock']);

    // Supervisor Inventory & Dashboard
    Route::get('/supervisor-stock', [ApiInventoryController::class , 'getSupervisorStock']); // Fixed likely typo in existing code or similar
    Route::get('/agent-dashboard', [ApiManagementController::class , 'getAgentDashboard']);
    Route::get('/supervisor-dashboard', [ApiManagementController::class , 'getSupervisorDashboard']);
    Route::post('/daily-load/{id}/start-trip', [ApiManagementController::class , 'startTrip']);
    Route::get('/supervisor-route', [ApiManagementController::class , 'getSupervisorRoute']);
    Route::get('/active-daily-load-details', [ApiManagementController::class , 'getActiveDailyLoadDetails']);
    Route::post('/complete-route', [ApiManagementController::class , 'completeRoute']);
    Route::get('/customer-detail/{id}', [ApiManagementController::class , 'getCustomerDetail']);
    Route::get('/customer-invoices/{customerId}', [ApiManagementController::class , 'getCustomerInvoices']);
    Route::get('/customer-returns/{customerId}', [ApiManagementController::class , 'getCustomerReturns']);
    Route::get('/invoice-items/{invoiceId}', [ApiManagementController::class , 'getInvoiceItems']);
    Route::post('/create-b2b-invoice', [ApiManagementController::class , 'createB2BInvoice']);
    Route::post('/process-standalone-return', [ApiManagementController::class , 'processStandaloneReturn']);
    Route::post('/collect-payment', [ApiManagementController::class , 'collectPayment']);
});