<?php

use Illuminate\Support\Facades\Route;
use Modules\CentralApplication\Http\Controllers\CentralApplicationController;
use Modules\CentralApplication\Http\Controllers\TenantController;

// Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
//     Route::apiResource('centralapplications', CentralApplicationController::class)->names('centralapplication');
// });

 Route::apiResource('tenants', TenantController::class)->names('tenants');
