<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// login route
Route::get('/', [LoginController::class, 'index'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// logout route
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

// dashboard route
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

// attendance routes
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::get('/employee', [AttendanceController::class, 'employee'])->name('employee');
