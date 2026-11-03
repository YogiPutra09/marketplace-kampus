<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductModerationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProductReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\WishlistController;

// 1. Redirect halaman utama ke daftar katalog produk
Route::get('/', [ProductController::class, 'home'])->name('home');

// 2. Katalog Publik
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// 3. Rute Terproteksi Pengguna (Wajib Login)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Form tambah & simpan produk baru (HARUS LEBIH DULU DARI {product})
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');

    // Halaman daftar produk milik user
    Route::get('/my-products', [ProductController::class, 'myProducts'])->name('products.my');

    // Form edit, perbarui, status, & hapus produk
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');

    // Fitur Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Rute Profil Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute ulasan penjual
    Route::post('/sellers/{seller}/reviews', [SellerReviewController::class, 'store'])
        ->name('seller.reviews.store');
    
    Route::post('/products/{product}/report', [ProductReportController::class, 'store'])
        ->name('products.report');
});

// 4. Rute Detail Produk (Diletakkan di bawah setelah create)
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// 5. RUTE KHUSUS ADMINISTRATOR (Wajib Login & Role Admin)

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::delete('/products/{product}', [ProductModerationController::class, 'destroy'])->name('products.destroy');
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::patch('/reports/{report}/dismiss', [ProductReportController::class, 'dismiss'])->name('reports.dismiss');
    
    // Manajemen Pengguna Khusus Admin:
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('users.toggleBan');
    });

require __DIR__.'/auth.php';