<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthenticationController;

//Admin Panel login logout routes
require __DIR__ . '/auth/auth.php';

//Admin Panel users routes
require __DIR__ . '/users/users.php';

//Blog routes
require __DIR__ . '/blogs/blogs.php';

//OG images
require __DIR__ . '/og-images/ogImages.php';

//Queries routes
require __DIR__ . '/queries/queries.php';

//Contacts routes
require __DIR__ . '/contacts/contacts.php';

//Job Portal routes
require __DIR__ . '/job-portal/jobPortal.php';

//Job Portal routes
require __DIR__ . '/CaseStudy/caseStudy.php';

//Seo service in all country routes
require __DIR__ . '/SeoService/seoService.php';

require __DIR__ . '/backups/backups.php';

require __DIR__ . '/userRoles/userRoles.php';

require __DIR__ . '/country-or-city-wise-page-content/country-or-city-wise-page-content.php';

require __DIR__ . '/status-label/status-label.php';

require __DIR__ . '/job-application/application.php';

require __DIR__ . '/companyDeck/companyDeck.php';

require __DIR__ . '/subscribe/subscribe.php';

require __DIR__ . '/export/export.php';

Route::get('/logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::middleware(['auth'])->group(function () {

    Route::get('/', [StaterkitController::class, 'home'])->name('home');

});



