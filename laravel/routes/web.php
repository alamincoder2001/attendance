<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AttendanceController;

Route::fallback(function () {
    return view('layouts.404');
})->middleware('auth');

// login route
Route::get('/', [LoginController::class, 'index'])->name('showLoginForm');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// logout route
Route::get('/logout', [HomeController::class, 'logout'])->name('logout');

// dashboard route
Route::get('/dashboard/{id?}', [HomeController::class, 'index'])->name('dashboard');
Route::get('/device-dashboard', [HomeController::class, 'deviceDashboard'])->name('device.dashboard');

// company profile route
Route::get('/company-profile', [HomeController::class, 'companyProfile'])->name('company.profile');
Route::post('/company-profile', [HomeController::class, 'companyProfile'])->name('company.profile.update');

// device routes
Route::get('/device', [DeviceController::class, 'create'])->name('device.create');
Route::match(['get', 'post'], '/get-device', [DeviceController::class, 'index'])->name('device.index');
Route::post('/add-device', [DeviceController::class, 'store'])->name('device.store');
Route::post('/update-device', [DeviceController::class, 'update'])->name('device.update');
Route::post('/delete-device', [DeviceController::class, 'destroy'])->name('device.destroy');

// attendance routes
Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance');
Route::get('/employee', [AttendanceController::class, 'employee'])->name('employee');
