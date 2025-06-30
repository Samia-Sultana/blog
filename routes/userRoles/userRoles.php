<?php

use App\Http\Controllers\Role\RoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index'])->name('roles');
    Route::post('/roles', [RoleController::class, 'store'])->name('create-role');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('edit-role');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('update-role');
    Route::delete('/roles', [RoleController::class, 'destroy'])->name('delete-role');
});


