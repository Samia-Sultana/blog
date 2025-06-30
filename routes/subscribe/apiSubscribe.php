<?php

use App\Http\Controllers\Subscribe\SubscribeController;
use Illuminate\Support\Facades\Route;

Route::post('/subscribe', [SubscribeController::class, 'store']);

