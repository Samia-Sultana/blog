<?php

use App\Http\Controllers\JobApplication\JobApplicationController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth',  'module:job-application'])->prefix('job')->group(function () {
    Route::get('application', [JobApplicationController::class, 'index'])->name('job-application');
    Route::get('view/application/{id}', [JobApplicationController::class, 'view'])->name('view.application');
    Route::post('update/application/status/{id}', [JobApplicationController::class, 'updateStatus'])->name('update.application.status');
    Route::post('/export', [JobApplicationController::class, 'export'])->name('job.export');
});
