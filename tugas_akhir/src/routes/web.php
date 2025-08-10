<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\BookingController as FrontendBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
// use App\Http\Controllers\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web".
|
*/

// Route Halaman Utama
Route::get('/', function () {
    return view('welcome'); // Ganti dengan view yang sesuai
})->name('landing');

Route::post('/midtrans/callback', [App\Http\Controllers\Frontend\BookingController::class, 'callback'])->name('midtrans.callback');

// --- RUTE UNTUK ALUR BOOKING (FRONTEND) ---
// Dapat diakses oleh semua orang, tetapi beberapa langkah memerlukan login
Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/step-one', [FrontendBookingController::class, 'stepOne'])->name('step-one');
    Route::post('/step-one', [FrontendBookingController::class, 'postStepOne'])->name('post.step-one');
    
    // Rute selanjutnya memerlukan sesi booking yang valid
    Route::middleware('booking.session')->group(function() {
        Route::get('/step-two', function() {
            // Logika untuk menampilkan halaman step-two akan ditangani di controller postStepOne
            // Rute ini ada untuk URL yang lebih bersih, namun view-nya dipanggil dari postStepOne
            // Jika user merefresh halaman ini, kita bisa arahkan kembali ke step-one
            if (!session()->has('booking.place_id')) {
                return redirect()->route('booking.step-one');
            }
            // Anda perlu memuat ulang data meja yang tersedia di sini jika diperlukan
            return view('frontend.booking.step-two', [
                'availableTables' => \App\Models\Table::where('place_id', session('booking.place_id'))->get() // Contoh sederhana
            ]);
        })->name('step-two');
        Route::post('/step-two', [FrontendBookingController::class, 'postStepTwo'])->name('post.step-two');

        Route::get('/step-three', [FrontendBookingController::class, 'stepThreeView'])->name('step-three');
        Route::post('/step-three', [FrontendBookingController::class, 'postStepThree'])->name('post.step-three')->middleware('auth');

        Route::get('/step-four', [FrontendBookingController::class, 'stepFourView'])->name('step-four');

        // Proses penyimpanan booking harus sudah login
        Route::post('/store', [FrontendBookingController::class, 'store'])->name('store')->middleware('auth');
    });

    Route::get('/success/{booking_code}', [FrontendBookingController::class, 'success'])->name('success');
});


// --- RUTE AUTENTIKASI DARI LARAVEL UI / BREEZE ---
// Ini akan membuat rute seperti /login, /register, /logout

// --- GRUP RUTE UNTUK ADMIN ---
// Hanya bisa diakses oleh user dengan role 'admin'
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Resource routes untuk CRUD data master
    Route::resource('places', PlaceController::class);
    Route::resource('tables', TableController::class)->except(['show']);
    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    
    // Rute untuk mengelola kategori
    Route::resource('categories', AdminCategoryController::class)->except(['show']);

    // Rute untuk mengelola booking
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/update-status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');
    
    // Rute untuk mengelola pengguna bisa ditambahkan di sini
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
});

// --- GRUP RUTE UNTUK CUSTOMER ---
// Hanya bisa diakses oleh user dengan role 'customer'
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings/{booking}', [App\Http\Controllers\Customer\DashboardController::class, 'show'])->name('bookings.show');
    
    // Rute baru untuk membatalkan booking
    Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\Customer\DashboardController::class, 'cancel'])->name('bookings.cancel');
});

// --- GRUP RUTE UNTUK OWNER ---
// Hanya bisa diakses oleh user dengan role 'owner'
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    // Contoh rute untuk laporan
    // Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports.index');
    // Route::get('/reports/generate', [OwnerReportController::class, 'generate'])->name('reports.generate');
Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
