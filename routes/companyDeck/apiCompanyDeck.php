<?php

use App\Http\Controllers\CompanyDeckController;
use Illuminate\Support\Facades\Route;

Route::post('/subscribe-company-deck', [CompanyDeckController::class, 'store']);

