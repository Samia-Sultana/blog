<?php

use App\Http\Controllers\Export\ExportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'module:export'])->prefix('export')->group(function () {
    Route::post('/home', [ExportController::class, 'export'])->name('export.create');
    Route::get('/home', [ExportController::class, 'index'])->name('export');
    Route::get('/{model}/{filename}', [ExportController::class, 'downloadExport'])->name('export.download');
    Route::delete('/{model}/{filename}', [ExportController::class, 'deleteExport'])->name('export.delete');
});
