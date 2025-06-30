<?php

use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\BlogCategoryController;


Route::middleware(['auth'])->group(function () {
    Route::get('/query', [QueryController::class, 'index'])->name('query.index');
});
