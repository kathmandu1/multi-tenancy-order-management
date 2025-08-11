<?php

use Illuminate\Support\Facades\Route;
use Modules\OrderManagement\Http\Controllers\OrderManagementController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ordermanagements', OrderManagementController::class)->names('ordermanagement');
});
