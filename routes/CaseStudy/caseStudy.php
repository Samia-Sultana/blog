<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ContentCaseStudyController;
use App\Http\Controllers\SeoCaseStudyController;
use App\Http\Controllers\SoftwareCaseStudyController;

Route::middleware(['auth'])->group(function () {

    Route::group(['prefix' => 'case-study'], function () {

        Route::get('/home', [CaseStudyController::class, 'view'])->name('case-study');

        Route::get('/create', [CaseStudyController::class, 'create'])->name('case.create');

        Route::post('/store', [CaseStudyController::class, 'store'])->name('case.store');

        Route::get('/{id}/edit', [CaseStudyController::class, 'edit'])->name('case.edit');

        Route::post('/{id}/update', [CaseStudyController::class, 'update'])->name('case.update');

        Route::delete('/{id}/destroy', [CaseStudyController::class, 'destroy'])->name('case.destroy');

        Route::post('/publish', [CaseStudyController::class, 'publish'])->name('case.publish');

        //Seo Routes

        Route::get('/{id}/seo', [SeoCaseStudyController::class, 'create'])->name('case.seo');

        Route::post('/{id}/seo/store', [SeoCaseStudyController::class, 'store'])->name('case.seo.store');

        Route::get('/seo/{id}/edit', [SeoCaseStudyController::class, 'edit'])->name('case.seo.edit');

        Route::post('/seo/{id}/update', [SeoCaseStudyController::class, 'update'])->name('case.seo.update');

        //Content Routes

        Route::get('/{id}/content', [ContentCaseStudyController::class, 'create'])->name('case.content');

        Route::post('/{id}/content/store', [ContentCaseStudyController::class, 'store'])->name('case.content.store');

        Route::get('/content/{id}/edit', [ContentCaseStudyController::class, 'edit'])->name('case.content.edit');

        Route::post('/content/{id}/update', [ContentCaseStudyController::class, 'update'])->name('case.content.update');

        //Software Routes

        Route::get('/{id}/software', [SoftwareCaseStudyController::class, 'create'])->name('case.software');

        Route::post('/{id}/software/store', [SoftwareCaseStudyController::class, 'store'])->name('case.software.store');

        Route::get('/software/{id}/edit', [SoftwareCaseStudyController::class, 'edit'])->name('case.software.edit');

        Route::post('/software/{id}/update', [SoftwareCaseStudyController::class, 'update'])->name('case.software.update');


    });
});
