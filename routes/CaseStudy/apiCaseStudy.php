<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContentCaseStudyController;
use App\Http\Controllers\SeoCaseStudyController;
use App\Http\Controllers\SoftwareCaseStudyController;

Route::middleware('throttle:600,1')->group(function () {

    Route::group(['prefix' => 'case-study'], function () {
        Route::get('/', [CaseStudyController::class, 'apiview'])->name('case.apiview');
        Route::get('/seo/{slug}', [SeoCaseStudyController::class, 'seo_view_for_api'])->name('case.seoApiview');

        Route::get('/content-writing/{slug}', [ContentCaseStudyController::class, 'view_for_api'])->name('case.seoApiview');

        Route::get('/software-development/{slug}', [SoftwareCaseStudyController::class, 'view_for_api'])->name('case.seoApiview');


    });
});
