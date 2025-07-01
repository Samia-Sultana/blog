<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\CaseStudyController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
});
Route::get('/blogs', [BlogController::class, 'apiIndex'])->name('blogs');

Route::get('/blog-single-fullwidth/{category}/{slug}', [BlogController::class, 'apiShow']);

Route::get('/blog-single-left-sidebar', function () {
    return view('blog-single-left-sidebar');
});
Route::get('/contact', function () {
    return view('contact');
});
Route::get('/blog-single', function () {
    return view('blog-single');
});

Route::get('/casestudies', [CaseStudyController::class, 'apiView'])->name('casestudies');
Route::get('/casestudy-details/{category}/{slug}', [CaseStudyController::class, 'apiShow']);
Route::get('/civillitigation', function () {
    return view('civillitigation');
});
Route::get('/commerciallitigation', function () {
    return view('commerciallitigation');
});
Route::get('/constructionlitigation', function () {
    return view('constructionlitigation');
});
Route::get('/contractualdisputes', function () {
    return view('contractualdisputes');
});
Route::get('/defamation', function () {
    return view('defamation');
});
Route::get('/faq', function () {
    return view('faq');
});
Route::get('/immigrationlaw', function () {
    return view('immigrationlaw');
});
Route::get('/mortgagedefense', function () {
    return view('mortgagedefense');
});
Route::get('/realestatelitigation', function () {
    return view('realestatelitigation');
});
Route::get('/service-single', function () {
    return view('service-single');
});
Route::get('/services', function () {
    return view('services');
});
Route::get('/team', function () {
    return view('team');
});
