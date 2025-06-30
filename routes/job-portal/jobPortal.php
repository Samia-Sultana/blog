<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobVacancyController;

Route::middleware(['auth'])->group(function () {

    Route::get('/job-portal', [JobVacancyController::class, 'view'])->name('job-portal');
    Route::get('/job-create', [JobVacancyController::class, 'create'])->name('job.create');
    Route::post('/job-store', [JobVacancyController::class, 'store'])->name('job.store');
    Route::get('/job-edit/{id}', [JobVacancyController::class, 'edit'])->name('job.edit');
    Route::post('/job-update/{id}', [JobVacancyController::class, 'update'])->name('job.update');
    Route::delete('/job-delete/{id}', [JobVacancyController::class, 'delete'])->name('job.delete');

    Route::post('/job-publish', [JobVacancyController::class, 'job_publish'])->name('job.job_publish');

    Route::post('/job/copy/{id}', [JobVacancyController::class, 'copy'])->name('job.copy');


});
