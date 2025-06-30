<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Contact\ContactController;

//create a contact api route
Route::post('/contact', [ContactController::class, 'store'])->name('create-contact');
