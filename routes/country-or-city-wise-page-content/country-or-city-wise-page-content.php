<?php

use App\Http\Controllers\CountryOrCityWisePageContent\CountryOrCityWisePageContentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'module:ai-seo-pages'])->prefix('ai-seo-pages')->group(function () {
    Route::get('/home', [CountryOrCityWisePageContentController::class, 'index'])->name('ai-seo-pages');
    Route::get('/create', [CountryOrCityWisePageContentController::class, 'create'])->name('pages.create');
    Route::post('/store', [CountryOrCityWisePageContentController::class, 'store'])->name('pages.store');
    Route::get('/edit/{id}', [CountryOrCityWisePageContentController::class, 'edit'])->name('pages.edit');
    Route::put('/update/{id}', [CountryOrCityWisePageContentController::class, 'update'])->name('pages.update');
    Route::delete('/delete/{id}', [CountryOrCityWisePageContentController::class, 'delete'])->name('pages.delete');
    Route::post('/pages/upload-country-city', [CountryOrCityWisePageContentController::class, 'uploadCountryCity'])->name('pages.upload-country-city');

    Route::post('/bulk-delete', [CountryOrCityWisePageContentController::class, 'bulkDelete'])->name('pages.bulkDelete');
    Route::post('/bulk-set-index', [CountryOrCityWisePageContentController::class, 'bulkSetIndex'])->name('pages.bulkSetIndex');
    Route::post('/bulk-set-published', [CountryOrCityWisePageContentController::class, 'bulkSetbulkSetPublished'])->name('pages.bulkSetPublished');
    Route::post('/{id}/toggle-publish', [CountryOrCityWisePageContentController::class, 'togglePublish'])->name('pages.togglePublish');
    Route::post('/export', [CountryOrCityWisePageContentController::class, 'pageExport'])->name('ai-page.export');

    Route::post('/bulk-publish-schedule', [CountryOrCityWisePageContentController::class, 'bulkPublishSchedule'])->name('pages.bulkPublishSchedule');
    Route::get('/schedule-graph-data', [CountryOrCityWisePageContentController::class, 'getScheduleGraphData'])->name('schedule.graph.data');
    Route::post('/schedule-graph-data/clear-cache', [CountryOrCityWisePageContentController::class, 'clearScheduleGraphCache'])->name('pages.schedule-graph.clear-cache');
    Route::get('/index-graph-data', [CountryOrCityWisePageContentController::class, 'getIndexGraphData'])->name('index.graph.data');
    Route::post('/index-graph-data/clear-cache', [CountryOrCityWisePageContentController::class, 'clearIndexGraphCache'])->name('pages.index-graph.clear-cache');


});

Route::middleware(['auth', 'module:ai-page-contacts'])->prefix('ai-page-contacts')->group(function () {
    Route::get('/home', [CountryOrCityWisePageContentController::class, 'aiContactIndex'])->name('ai-page-contacts');
    Route::post('/home', [CountryOrCityWisePageContentController::class, 'export'])->name('ai-page-contacts.export');
});
