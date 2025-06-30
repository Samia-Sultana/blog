<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contact\ContactController;


Route::middleware(['auth', 'module:contacts'])->group(function () {
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts');
    Route::get('/contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::put('/contacts/{id}/update-status', [ContactController::class, 'updateStatus'])->name('contacts.update-status');
    Route::post('/contacts/export', [ContactController::class, 'export'])->name('contacts.export');
});
