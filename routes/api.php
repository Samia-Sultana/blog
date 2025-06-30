<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Blog api routes
require __DIR__ . '/blogs/apiBlogs.php';
require __DIR__ . '/queries/apiQueries.php';
require __DIR__ . '/subscription/apiSubscription.php';
require __DIR__ . '/contacts/apiContacts.php';

require __DIR__ . '/CaseStudy/apiCaseStudy.php';

require __DIR__ . '/SeoService/seoServiceApi.php';

require __DIR__ . '/companyDeck/apiCompanyDeck.php';

require __DIR__ . '/country-or-city-wise-page-content/api-country-or-city-wise-page-content.php';

require __DIR__ . '/ai-content-generate/ai-content-generate.php';

require __DIR__ . '/status-label/status-label-api.php';

require __DIR__ . '/job-application/application-api.php';

require __DIR__ . '/subscribe/apiSubscribe.php';

Route::post('db-backup', [AuthenticationController::class, 'createBackup']);
Route::post('public-folder-backup', [AuthenticationController::class, 'backupPublicFolder']);


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('send-subscription-email', [MailController::class, 'sendSubscriptionEmail']);


Route::get('/job-portal', [JobVacancyController::class, 'view_job']);
Route::get('/job-portal/{id}', [JobVacancyController::class, 'view_job_single']);

// Route::get('/email-template-view', function () {
//     $contact = [
//         'name' => 'John Doe',
//         'email' => 'john.doe@example.com',
//         'phone' => '+1234567890',
//         'company' => 'Example Inc.',
//         'subject' => 'Inquiry about services',
//         'subsubject' => 'Web Development',
//         'message' => 'I would like to know more about your web development services. I would like to know more about your web development services.I would like to know more about your web development services.',
//         'country' => 'Bangladesh',
//     ];

//     $ip = '244.178.44.111';
//     return view('email.newContactMail', compact('contact', 'ip'));
// });

