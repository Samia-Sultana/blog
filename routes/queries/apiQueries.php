<?php

use App\Http\Controllers\QueryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\Blog\BlogCategoryController;

//post queries api routes
Route::get('/query', [QueryController::class, 'index'])->name('queries');
Route::post('/query', [QueryController::class, 'store'])->name('create-query');
