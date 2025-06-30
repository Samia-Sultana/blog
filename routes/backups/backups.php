<?php

use App\Http\Controllers\Backup\BackupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/backup', [BackupController::class, 'index'])->name('backup');
    Route::get('/backup-create', [BackupController::class, 'create'])->name('backup.create');
    Route::get('/download/{folder}', [BackupController::class, 'downloadBackup'])->name('backup.download');
    Route::delete('/backup-delete', [BackupController::class, 'destroy'])->name('backup.delete');
});
