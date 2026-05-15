<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VaccinationBookingController;
use App\Http\Controllers\Admin\HealthScreeningBookingController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\VaccinationController;
use App\Http\Controllers\Admin\HealthScreeningController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\SettingController;

// Admin Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

// User Management
Route::resource('users', UserController::class, ['as' => 'admin']);

// These routes will be implemented as needed
Route::resource('doctors', DoctorController::class, ['as' => 'admin']);
Route::resource('vaccinations', \App\Http\Controllers\VaccinationController::class, ['as' => 'admin']);
Route::resource('vaccination-bookings', \App\Http\Controllers\VaccinationBookingController::class, ['as' => 'admin']);
Route::resource('health-screenings', \App\Http\Controllers\HealthScreeningController::class, ['as' => 'admin']);
Route::resource('health-screening-bookings', \App\Http\Controllers\HealthScreeningBookingController::class, ['as' => 'admin']);
Route::resource('messages', MessageController::class, ['as' => 'admin']);
Route::resource('contacts', ContactController::class, ['as' => 'admin']);
Route::get('settings', [SettingController::class, 'index'])->name('admin.settings.index');
Route::post('settings', [SettingController::class, 'update'])->name('admin.settings.update');