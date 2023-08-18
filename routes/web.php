<?php

use App\Http\Controllers\BoxController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\VoteController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
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
Route::get('/user/{username}', [UserController::class, 'show'])->name('users.show');
Route::get('/user/{username}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/user/{username}', [UserController::class, 'update'])->name('users.update');
Route::delete('/user/{username}', [UserController::class, 'destroy'])->name('users.destroy');

Route::resource('/novels', NovelController::class);
Route::post('/novels/{novel}/comments/store', [NovelController::class, 'commentStore'])->name('novels.comments.store');
Route::delete('/novels/{novel}/comments/{comment}/delete', [NovelController::class, 'commentDestroy'])->name('novels.comments.destroy');
Route::resource('/novels/{novel}/chapters', ChapterController::class)->except(['index']);
Route::post('/novels/{novel}/chapters/{chapter}/comments/store', [ChapterController::class, 'commentStore'])->name('chapters.comments.store');
Route::delete('/novels/{novel}/chapters/{chapter}/comments/{comment}/delete', [ChapterController::class, 'commentDestroy'])->name('chapters.comments.destroy');
Route::post('/novels/search', [NovelController::class, 'search'])->name('novels.search');
Route::resource('/tags', TagController::class)->except(['show']);
Route::resource('/categories', CategoryController::class)->except(['show']);
Route::resource('/boxes', BoxController::class);
Route::post('/boxes/{box}/novels/{novel}/add', [BoxController::class, 'add'])->name('boxes.novels.add');
Route::delete('/boxes/{box}/novels/{novel}/remove', [BoxController::class, 'remove'])->name('boxes.novels.remove');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports/{id}/{type}/add', [ReportController::class, 'add'])->name('reports.add');
Route::delete('/reports/{id}/{type}/remove', [ReportController::class, 'remove'])->name('reports.remove');
Route::post('/votes/reports/{id}/users/{user}/accept', [VoteController::class, 'accept'])->name('votes.reports.accept');
Route::post('/votes/reports/{id}/users/{user}/reject', [VoteController::class, 'reject'])->name('votes.reports.reject');