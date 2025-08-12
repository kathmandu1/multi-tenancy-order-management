<?php

use Illuminate\Support\Facades\Route;
use Modules\CentralApplication\Http\Controllers\CentralApplicationController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Route::resource('centralapplications', CentralApplicationController::class)->names('centralapplication');
});
