<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SlotDeliveryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataRekapController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    // Register
    Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
    Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    /*
    |------------------------------------------------------------------------
    | Dashboard Routes
    |------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.redirect');

    // Role-based Dashboard
    Route::get('/admin/dashboard', fn() => view('dashboard.admin'))->name('admin.dashboard');
    Route::get('/user/dashboard', fn() => view('dashboard.user'))->name('user.dashboard');

    /*
    |------------------------------------------------------------------------
    | Request Management Routes
    |------------------------------------------------------------------------
    */
    Route::prefix('requests')->group(function () {
        // Create Request
        Route::get('/create', [RequestController::class, 'create'])->name('requests.create');
        Route::post('/', [RequestController::class, 'store'])->name('requests.store');

        // Request Details & Actions
        Route::get('/{id}/show', [RequestController::class, 'show'])->name('requests.show');
        Route::get('/preview/{id}', [RequestController::class, 'preview'])->name('requests.preview');
        Route::get('/download/{filename}', [RequestController::class, 'download'])->name('requests.download');
        Route::match(['patch', 'put'], '/{id}/status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');
        Route::get('/cabang/{id}', [RequestController::class, 'showByCabang'])->name('requests.percabang');

        // CRUD Operations
        Route::resource('/', RequestController::class)->except(['create', 'store'])->parameter('', 'request');
    });

    /*
    |------------------------------------------------------------------------
    | Slot Delivery Routes
    |------------------------------------------------------------------------
    */
    Route::prefix('slot-deliveries')->group(function () {
        // Create Slot
        Route::get('/create', [SlotDeliveryController::class, 'create'])->name('slot-deliveries.create');
        Route::post('/', [SlotDeliveryController::class, 'store'])->name('slot-deliveries.store');

        // CRUD Operations
        Route::resource('/', SlotDeliveryController::class)->except(['create', 'store'])->parameter('', 'slot_delivery');
    });

    /*
    |------------------------------------------------------------------------
    | Data Rekap Routes
    |------------------------------------------------------------------------
    */
    Route::prefix('datarekaps')->group(function () {
        // Create Rekap
        Route::get('/create', [DataRekapController::class, 'create'])->name('datarekaps.create');
        Route::post('/', [DataRekapController::class, 'store'])->name('datarekaps.store');

        // CRUD Operations
        Route::resource('/', DataRekapController::class)->except(['create', 'store'])->parameter('', 'datarekap');
    });

    /*
    |------------------------------------------------------------------------
    | Profile Routes
    |------------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
    });
});