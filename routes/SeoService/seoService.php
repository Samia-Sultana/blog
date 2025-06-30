<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeoServiceInAllCountryController;

Route::middleware(['auth'])->group(function () {

    Route::get('/seo-service', [SeoServiceInAllCountryController::class, 'view'])->name('seo-service');
    Route::get('/seo-service-create', [SeoServiceInAllCountryController::class, 'create'])->name('seo.create');
    Route::post('/seo-service-store', [SeoServiceInAllCountryController::class, 'store'])->name('seo.store');
    Route::get('/seo-service-edit/{id}', [SeoServiceInAllCountryController::class, 'edit'])->name('seo.edit');
    Route::post('/seo-service-update/{id}', [SeoServiceInAllCountryController::class, 'update'])->name('seo.update');
    Route::delete('/seo-service-delete/{id}', [SeoServiceInAllCountryController::class, 'delete'])->name('seo.delete');  

    Route::post('/seo-service-publish', [SeoServiceInAllCountryController::class, 'publish'])->name('seo.publish');
    
});
