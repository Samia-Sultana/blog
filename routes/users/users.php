<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::middleware(['auth', 'module:users'])->group(function () {

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('create-user');
    Route::delete('/users', [UserController::class, 'destroy'])->name('delete-user');


});
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('edit-user');
Route::put('/users/{user}', [UserController::class, 'update'])->name('update-user');
Route::get('/user-profile', [UserController::class, 'view'])->name('userProfile');


