<?php

use App\Http\Controllers\Subscribe\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'module:subscribe'])->group(function () {
    Route::get('/subscribe', [SubscribeController::class, 'index'])->name('subscribe');
    Route::post('/subscribe/export', [SubscribeController::class, 'export'])->name('subscribe.export');
});
