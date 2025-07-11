<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Blog\BlogController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\Contact\ContactController;

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\AuthenticationController;

Route::get('/', [HomeController::class, 'index'])->name('client.home');

Route::get('/about', function () {
    return view('about');
});
Route::get('/blogs', [BlogController::class, 'apiIndex'])->name('blogs');

Route::get('/blog/{category}/{slug}', [BlogController::class, 'apiShow']);

Route::get('/blog-single-left-sidebar', function () {
    return view('blog-single-left-sidebar');
});
Route::get('/contact', function () {
    return view('contact');
});

Route::post('/contact/mail', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thank-you', function () {
    return view('thankyou');
})->name('thank.you');

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

//admin panel routes


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

Route::middleware(['auth'])->prefix('admin')->group(function () {

    Route::get('/', [StaterkitController::class, 'home'])->name('home');

});

