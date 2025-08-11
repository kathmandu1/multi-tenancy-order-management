<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderManagement\Http\Controllers\CustomerController;
use Modules\OrderManagement\Http\Controllers\OrderController;
use Modules\OrderManagement\Http\Controllers\OrderManagementController;
use Modules\OrderManagement\Http\Controllers\ProductController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ordermanagements', OrderManagementController::class)->names('ordermanagement');
});

Route::apiResource('customers', CustomerController::class)->names('customers');
Route::apiResource('products', ProductController::class)->names('products');
Route::apiResource('orders', OrderController::class)->names('orders');
