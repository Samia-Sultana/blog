<?php

use App\Http\Controllers\CountryOrCityWisePageContent\CountryOrCityWisePageContentController;
use Illuminate\Support\Facades\Route;

Route::get('page/{slug}', [CountryOrCityWisePageContentController::class, 'getBySlug']);
Route::post('/ai-page/contact', [CountryOrCityWisePageContentController::class, 'contact']);
