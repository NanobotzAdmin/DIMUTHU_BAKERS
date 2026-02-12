<?php

use App\Http\Controllers\ApiUserController;
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
});