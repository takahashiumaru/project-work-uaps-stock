<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\AdminTrainingCertificateController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController; // added

// Page
Route::get('/profile/{id}', [UserController::class, 'profile'])->name('users.profile');
Route::resource('products', ProductController::class)->middleware('auth'); // added
Route::resource('requests', ProductController::class)->middleware('auth'); // added

// API
Route::get('/', [LoginController::class, 'login'])->name('login');
Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/verify-otp', [LoginController::class, 'showOtpForm'])->name('verify.otp.form');
Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('verify.otp');


Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('/forgot-password', [LoginController::class, 'sendForgotPassword'])->name('forgot.password.send');
Route::post('/forgot-password/verify', [LoginController::class, 'verifyForgotPassword'])->name('forgot.password.verify');
Route::get('/forgot-password/change', [LoginController::class, 'showChangePasswordForm'])->name('change.password.form');
Route::post('/forgot-password/change', [LoginController::class, 'changePassword'])->name('change.password');
Route::post('/resend-otp', [LoginController::class, 'resendOtp'])->name('resend.otp');

Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

Route::get('/change-password', [LoginController::class, 'showChangePasswordForm'])->name('change.password')->middleware('auth');
Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('update.password');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::post('/update-photo/{userId}', [UserController::class, 'updatePhoto'])->name('user.updatePhoto');
Route::put('/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::get('/apron', [UserController::class, 'indexApron'])->name('users.apron');
Route::get('/bge', [UserController::class, 'indexBGE'])->name('users.bge');
Route::get('/office', [UserController::class, 'indexOffice'])->name('users.office');