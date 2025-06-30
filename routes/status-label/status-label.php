<?php

use App\Http\Controllers\StatusLabel\StatusLabelController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'module:status-label'])->prefix('status-label')->group(function () {
    Route::get('/', [StatusLabelController::class, 'index'])->name('status-label');
    Route::post('/store', [StatusLabelController::class, 'store'])->name('status-label.store');
    Route::get('/{id}/edit', [StatusLabelController::class, 'edit'])->name('status-label.edit');
    Route::put('/{id}', [StatusLabelController::class, 'update'])->name('status-label.update');
    Route::delete('/delete', [StatusLabelController::class, 'delete'])->name('status-label.delete');
});
