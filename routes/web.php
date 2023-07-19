<?php

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\UserRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// email verification
// event and listener
Route::view('/', 'home')->name('home');
Route::view('/register', 'register')->name('register.view');
Route::post('/register', UserRegisterController::class)->name('register.post');
Route::view('/login', 'login')->name('login.view');
Route::post('/login', UserLoginController::class)->name('login.post');
Route::get('/logout', LogoutController::class)->name('logout');
Route::view('/forgot-password', 'forgotpassword.send')->name('forgotpassword.view');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendEmail'])->name('forgotpassword.send');
Route::view('/reset-password/{email}/{ticket}', 'forgotpassword.reset')->middleware('valid-ticket')->name('resetpassword.view');
Route::post('/reset-password/{email}/{ticket}', [ForgotPasswordController::class, 'resetPassword'])->middleware('valid-ticket')->name('resetpassword.reset');
Route::view('/email-verification', 'emailverification.send')->name('emailverification.view');
Route::post('/email-verification', [EmailVerificationController::class, 'sendEmail'])->name('emailverification.send');
Route::get('/email-verification/{email}/{ticket}', [EmailVerificationController::class, 'verify'])->middleware('valid-ticket')->name('emailverification.verify');