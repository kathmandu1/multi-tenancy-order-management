<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderManagement\Http\Controllers\CustomerController;
use Modules\OrderManagement\Http\Controllers\CustomerShippingAddressController;
use Modules\OrderManagement\Http\Controllers\OrderController;
use Modules\OrderManagement\Http\Controllers\OrderManagementController;
use Modules\OrderManagement\Http\Controllers\OrderTrackingController;
use Modules\OrderManagement\Http\Controllers\ProductController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ordermanagements', OrderManagementController::class)->names('ordermanagement');
});

Route::apiResource('customers', CustomerController::class)->names('customers');
Route::apiResource('customers.shippingaddresses', CustomerShippingAddressController::class)
    ->shallow() // optional: makes tracking routes not require {customer} for show/update/delete
    ->names('customers.shippingaddresses');

Route::apiResource('products', ProductController::class)->names('products');
Route::apiResource('orders', OrderController::class)->names('orders');
Route::apiResource('orders.trackings', OrderTrackingController::class)
    ->shallow() // optional: makes tracking routes not require {order} for show/update/delete
    ->names('orders.trackings');
