<?php

use App\Http\Controllers\SendRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\insertEmailValidationCodeController;
use App\Http\Controllers\VerifyEmailForSendingRequestController;
use App\Http\Controllers\contactFormController;
use App\Http\Controllers\ValidateModeratorEmailController;
use App\Http\Controllers\ValidateModeratorCodeController;
use App\Http\Controllers\ModeratorUserInfoValidationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\requestsViewController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/test', function () {
    return view('test');
});

Route::get('/bootstrap', function () {
    return view('layouts.carousel');
});

// Contact route
Route::get('/contact', function () {
    return view('contact');
})->name('contact');



// About route
Route::get('/about', function () {
    return view('about');
})->name('about');

// Autentificate route
Route::get('/autentificate', function () {
    return view('autentificate');
})->name('autentificate');

// Privacy policy route
Route::get('/policy', function () {
    return view('privacy_policy');
})->name('privacy_policy');



//Account route
Route::get('/send-request', function () {
    return view('send_request');
})->name('send_request');


//send request to stay in CV form 
Route::post('/send-request-form', [SendRequestController::class, 'mainSendRequestMethod'])->name('sendRequest.form');
Route::post('/verify-email-for-sending-request', [VerifyEmailForSendingRequestController::class, 'verify_email_for_sending_request_controller_main'])->name('verify_email_for_sending_request_controller.form');

//insert insert_email_validation_code 
Route::get('/insert_email_validation_code', function () {
    return view('insert_email_validation_code');
})->name('insert_email_validation_code');

// Narator view 
Route::get('/moderator', function () {
    return view(view: 'moderator.moderator');
})->name('narator');


// narator view code validation route
Route::get('/moderator-validation-code', function () {
    return view(view: 'moderator.CodeValidationModerator');
})->name('naratorCodeValidation');

// Narator view create moderator route
Route::get('/moderator-create', function () {
    return view(view: 'moderator.CreateModerator');
})->name('createNarator');

// Route for dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/dashboardIsAdmin', [DashboardController::class, 'IsAdmin'])->middleware('auth')->name('dashboardIsAdmin');
Route::get('/requestsView', [requestsViewController::class, 'index'])->middleware(['auth'])->name('requestView');


//verify email form route
Route::post('/verify-email-val-code', [insertEmailValidationCodeController::class, 'MainInsertEmailValidationCodeMethod'])->name('verifyEmailCode.form');

//concact form route
Route::post('/contactForm',[contactFormController::class,'mainContactFormController'])->name('contactForm.form');
Route::post('/ModeratorEmailForm',[ValidateModeratorEmailController::class,'ValidateNaratorEmailControllerMainMethod'])->name('ModeratorEmailValidation.form');
Route::post('/ModeratorCondeForm',[ValidateModeratorCodeController::class,'ValidateNaratorCodeControllerMainMethod'])->name('ModeratorCodeValidation.form');
Route::post('/ModeratorUserForm',[ModeratorUserInfoValidationController::class,'NaratorUserInfoValidationControllerMainMethod'])->name('ModeratorUser.form');
