<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Management\UserController;
use App\Http\Controllers\Management\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Management\DashboardController as AdminDashboardController;
use App\Http\Controllers\Resident\ComplaintController as ResidentComplaintController;
use App\Http\Controllers\Resident\DashboardController as ResidentDashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// diretcho login page
Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// ADMIN ROUTES
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('complaints');
    Route::patch('/complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaint-status-update');
    Route::get('/complaints/filter/category/{category}', [AdminComplaintController::class, 'filterByCategory'])->name('complaints-filter-category');
    Route::get('/complaints/filter/priority/{priority}', [AdminComplaintController::class, 'filterByPriority'])->name('complaints-filter-priority');
    Route::get('/complaints/filter/status/{status}', [AdminComplaintController::class, 'filterByStatus'])->name('complaints-filter-status');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/archived', [AdminComplaintController::class, 'archived'])
    ->name('archived');
    Route::get('/profile', fn () => view('management.profile'))->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// RESIDENTS ROUTES
Route::middleware('auth')->prefix('resident')->name('resident.')->group(function () {
    Route::get('/dashboard', [ResidentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/submit-complaint', [ResidentComplaintController::class, 'create'])->name('submit-complaint');
    Route::post('/submit-complaint', [ResidentComplaintController::class, 'store'])->name('submit-complaint.store');
    Route::get('/complaints', [ResidentComplaintController::class, 'index'])->name('complaints');
    Route::get('/notifications', fn () => view('resident.notifications'))->name('notifications');
    Route::get('/profile', fn () => view('resident.profile'))->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});