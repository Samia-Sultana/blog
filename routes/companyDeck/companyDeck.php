<?php

use App\Http\Controllers\CompanyDeckController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'module:company-deck'])->group(function () {
    Route::get('/company-deck', [CompanyDeckController::class, 'index'])->name('company-deck');
    Route::post('/company-deck', [CompanyDeckController::class, 'export'])->name('company-deck.export');
});
