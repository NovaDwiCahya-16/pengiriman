
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SlotDeliveryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataRekapController;
use App\Http\Controllers\ProfileController;
use App\Models\Request;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| AUTH & LOGIN / REGISTER / LOGOUT
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROLE-BASED DASHBOARD (ADMIN / USER)
|--------------------------------------------------------------------------
*/

Route::get('/requests/cabang/{id}', [RequestController::class, 'showByCabang'])->name('permintaan.percabang');
Route::middleware(['auth'])->get('/admin/dashboard', function () {
    return view('dashboard.admin');
})->name('admin.dashboard');
Route::middleware(['auth'])->get('/user/dashboard', function () {
    return view('dashboard.user');
})->name('user.dashboard');

/*
|--------------------------------------------------------------------------
| MAIN ROUTES (Requires Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD UTAMA
    |--------------------------------------------------------------------------
    */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    /*
    |--------------------------------------------------------------------------
    | PERMINTAAN
    |--------------------------------------------------------------------------
    */
    // Perbaikan route di web.php
    Route::get('/request', [RequestController::class, 'adminRequest'])->name('requests');
    Route::get('/manage-request', [RequestController::class, 'manageRequest'])->name('manage.request');
    Route::post('/store-request', [RequestController::class, 'storeRequest'])->name('store.request');
    Route::post('/edit-request', [RequestController::class, 'editRequest'])->name('edit.request');
    Route::post('/delete-request', [RequestController::class, 'deleteRequest'])->name('delete.request');
    Route::get('/request/{id}/detail', [RequestController::class, 'getRequestDetail'])->name('request.detail');
    /*
    |--------------------------------------------------------------------------
    | SLOT PENGIRIMAN
    |--------------------------------------------------------------------------
    */
    // Route::get('/slot-deliveries/create', [SlotDeliveryController::class, 'create'])->name('slot-deliveries.create');
    // Route::post('/slot-deliveries', [SlotDeliveryController::class, 'store'])->name('slot-deliveries.store');
    // Route::resource('slot-deliveries', SlotDeliveryController::class)->except(['create', 'store']);

    Route::get('/slot', [SlotDeliveryController::class, 'adminSlot'])->name('slots');
    Route::get('/manage-slot', [SlotDeliveryController::class, 'manageSlot'])->name('manage.slot');
    Route::post('/store-slot', [SlotDeliveryController::class, 'storeSlot'])->name('store.slot');
    Route::post('/edit-slot', [SlotDeliveryController::class, 'editSlot'])->name('edit.slot');
    Route::post('/delete-slot', [SlotDeliveryController::class, 'deleteSlot'])->name('delete.slot');
    Route::get('/slot/{id}/detail', [SlotDeliveryController::class, 'getSlotDetail'])->name('slot.detail');
    /*
    |--------------------------------------------------------------------------
    | DATA REKAP
    |--------------------------------------------------------------------------
    */
Route::middleware('auth')->group(function () {
    Route::get('/datarekap', [DataRekapController::class, 'index'])->name('datarekaps.index');
    Route::get('/manage-datarekap', [DataRekapController::class, 'manageDataRekap'])->name('manage.datarekap');
    Route::post('/store-datarekap', [DataRekapController::class, 'storeDataRekap'])->name('store.datarekap');
Route::post('/editDataRekapModal', [DataRekapController::class, 'editDataRekap'])->name('edit.datarekap');
    Route::post('/delete-datarekap', [DataRekapController::class, 'deleteDataRekap'])->name('delete.datarekap');
Route::get('/datarekaps/{id}/detail', [DataRekapController::class, 'getDataRekapDetail'])->name('datarekaps.detail');
});
    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/edit-profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/requests/preview/{id}', [RequestController::class, 'preview'])->name('requests.preview');

    //new
});
