<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;

// Admin Panel Routes
Route::get('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('login', [AuthenticationController::class, 'loginConfirm'])->name('loginConfirm');
Route::post('logout', [AuthenticationController::class, 'logout'])->name('logout');


Route::get('forgot-password', [AuthenticationController::class, 'forgotPassword'])->name('forgotPassword');
Route::get('otp', [AuthenticationController::class, 'otp_index'])->name('otpIndex');
Route::post('otp', [AuthenticationController::class, 'otp'])->name('otp');
Route::post('verify-otp', [AuthenticationController::class, 'verifyOtp'])->name('verifyOtp');

Route::get('new-password', [AuthenticationController::class, 'newPasswordIndex'])->name('newPasswordIndex');
Route::post('new-password', [AuthenticationController::class, 'newPassword'])->name('newPassword');
