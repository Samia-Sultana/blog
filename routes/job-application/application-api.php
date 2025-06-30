<?php

use App\Http\Controllers\JobApplication\JobApplicationController;
use Illuminate\Support\Facades\Route;


Route::prefix('job')->group(function () {
    Route::post('application/inside', [JobApplicationController::class, 'storeApplication']);
    Route::post('application/outside', [JobApplicationController::class, 'storeApplication']);
});
