
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

// ✅ Form login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');

// ✅ Proses login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// ✅ Form register
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');

// ✅ Proses register
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

// ✅ Logout
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| ROLE-BASED DASHBOARD (ADMIN / USER)
|--------------------------------------------------------------------------
*/

Route::get('/requests/cabang/{id}', [RequestController::class, 'showByCabang'])->name('permintaan.percabang');

// ✅ Admin Dashboard
Route::middleware(['auth'])->get('/admin/dashboard', function () {
    return view('dashboard.admin');
})->name('admin.dashboard');

// ✅ User Dashboard
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

    // ✅ Dashboard utama (grafik dan ringkasan)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | PERMINTAAN
    |--------------------------------------------------------------------------
    */

    // ✅ Form create permintaan
    Route::get('/requests/create', [RequestController::class, 'create'])->name('requests.create');

    // ✅ Simpan permintaan baru
    Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

    // ✅ Download file permintaan
    Route::get('/requests/download/{filename}', [RequestController::class, 'download'])->name('requests.download');

    // ✅ Detail permintaan
    Route::get('/requests/{id}/show', [RequestController::class, 'show'])->name('requests.show');

    // ✅ Update status (patch dan put)
    Route::patch('/requests/{id}/status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');
    Route::put('/requests/{id}/status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');

    // ✅ CRUD Request (kecuali create/store karena sudah ditentukan di atas)
    Route::resource('requests', RequestController::class)->except(['create', 'store']);

    /*
    |--------------------------------------------------------------------------
    | SLOT PENGIRIMAN
    |--------------------------------------------------------------------------
    */

    // ✅ Form create slot
    Route::get('/slot-deliveries/create', [SlotDeliveryController::class, 'create'])->name('slot-deliveries.create');

    // ✅ Simpan slot baru
    Route::post('/slot-deliveries', [SlotDeliveryController::class, 'store'])->name('slot-deliveries.store');

    // ✅ CRUD Slot Pengiriman
    Route::resource('slot-deliveries', SlotDeliveryController::class)->except(['create', 'store']);

    /*
    |--------------------------------------------------------------------------
    | DATA REKAP
    |--------------------------------------------------------------------------
    */

    // ✅ Form create rekap
    Route::get('/datarekaps/create', [DataRekapController::class, 'create'])->name('datarekaps.create');

    // ✅ Simpan rekap baru
    Route::post('/datarekaps', [DataRekapController::class, 'store'])->name('datarekaps.store');

    // ✅ CRUD Data Rekap
    Route::resource('datarekaps', DataRekapController::class)->except(['create', 'store']);

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    // ✅ Edit & Update profil user
    Route::get('/edit-profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/edit-profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route untuk menampilkan isi file Excel permintaan dalam bentuk popup/preview
    Route::get('/requests/preview/{id}', [RequestController::class, 'preview'])->name('requests.preview');

});