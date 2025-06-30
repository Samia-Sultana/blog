<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SeoServiceInAllCountryController;

Route::get('/seo/{slug}', [SeoServiceInAllCountryController::class, 'view_seo_service_single']);