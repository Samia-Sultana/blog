<?php

use App\Http\Controllers\StatusLabel\StatusLabelController;
use Illuminate\Support\Facades\Route;


Route::prefix('status-label')->group(function () {
    Route::get('/department', [StatusLabelController::class, 'getDepartments']);
    Route::get('/position', [StatusLabelController::class, 'getPositions']);
});
