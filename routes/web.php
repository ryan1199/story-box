<?php

use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\UserRegisterController;
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