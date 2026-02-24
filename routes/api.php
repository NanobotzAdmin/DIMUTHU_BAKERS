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
    Route::post('/login', [ApiUserController::class, 'login']);

});


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiUserController::class, 'logout']);

    Route::post('/create-customer',[ApiCustomerController::class, 'createCustomer']);

    // Management Routes
    Route::get('/drivers', [ApiManagementController::class, 'getDrivers']);
    Route::post('/create-driver', [ApiManagementController::class, 'createDriver']);

    Route::get('/supervisors', [ApiManagementController::class, 'getSupervisors']);
    Route::post('/create-supervisor', [ApiManagementController::class, 'createSupervisor']);

    Route::get('/vehicles', [ApiManagementController::class, 'getVehicles']);
    Route::post('/create-vehicle', [ApiManagementController::class, 'createVehicle']);


    // Order Request Routes
    Route::get('/order-request', [ApiGRNController::class, 'index']);
    Route::get('/order-request/products', [ApiGRNController::class, 'getProducts']);
    Route::post('/order-request/create', [ApiGRNController::class, 'createOrderRequest']);
    Route::post('/order-request/{id}/payment', [ApiGRNController::class, 'addPayment']);
    Route::get('/order-request/{id}', [ApiGRNController::class, 'show']);
    Route::post('/order-request/{id}/confirm', [ApiGRNController::class, 'confirmOrder']);

     // Order Management Workflow Routes - Agents only handle completion via mobile
    Route::post('/order-request/complete', [DistributorAndSalesManagementController::class, 'completeOrder']);

    // Agent Inventory
    Route::get('/agent-stock', [ApiInventoryController::class, 'getAgentStock']);
});