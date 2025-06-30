<?php

use App\Http\Controllers\AIContentGenerate\AIContentGenerateController;
use Illuminate\Support\Facades\Route;

Route::post('ai-content-generate', [AIContentGenerateController::class, 'generateContentForApi']);
