<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\User\OrderController; 
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\ShopController;

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK (Dapat diakses tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Lihat semua produk & detail produk
Route::get('/products', [FrontendProductController::class, 'index'])->name('product.index');
Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('product.show');

// Lihat profil toko seller
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');

/*
|--------------------------------------------------------------------------
| RUTE PENGGUNA TEROTENTIKASI (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Pengalihan dashboard berdasarkan role
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('product.index');
    })->name('dashboard');

    // Pengaturan Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Keranjang Belanja (Cart)
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::patch('/cart/{cart}/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cart}/remove', [CartController::class, 'removeItem'])->name('cart.remove');

    // Alur Checkout
    Route::get('/checkout', [TransactionController::class,'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout/success', [TransactionController::class, 'success'])->name('checkout.success');

    // Riwayat Pesanan Customer
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{transaction}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::delete('/my-orders/{transaction}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Cetak Invoice
    Route::get('/transactions/{transaction}/print-invoice', [TransactionController::class, 'printInvoice'])
        ->name('transactions.print-invoice');

    // Fitur Ulasan (Sinkronisasi Produk & Toko)
    Route::post('/my-orders/{transaction}/product-review', [\App\Http\Controllers\Frontend\ReviewController::class, 'storeProductReview'])->name('reviews.store-product');
    Route::post('/my-orders/{transaction}/store-review', [\App\Http\Controllers\Frontend\ReviewController::class, 'storeStoreReview'])->name('reviews.store-store');
    
    // Hapus Ulasan
    Route::delete('/reviews/product/{id}', [\App\Http\Controllers\Frontend\ReviewController::class, 'destroyProductReview'])->name('reviews.destroy-product');
    Route::delete('/reviews/store/{id}', [\App\Http\Controllers\Frontend\ReviewController::class, 'destroyStoreReview'])->name('reviews.destroy-store');

    // Fitur Chat Realtime
    Route::get('/chat', [\App\Http\Controllers\Frontend\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{receiverId}', [\App\Http\Controllers\Frontend\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat', [\App\Http\Controllers\Frontend\ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/messages/{receiverId}', [\App\Http\Controllers\Frontend\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::delete('/chat/{receiverId}', [\App\Http\Controllers\Frontend\ChatController::class, 'destroy'])->name('chat.destroy');
    Route::delete('/chat/message/{messageId}', [\App\Http\Controllers\Frontend\ChatController::class, 'destroyMessage'])->name('chat.message.destroy');
});

/*
|--------------------------------------------------------------------------
| RUTE ADMIN & SELLER (Melalui Middleware Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Manajemen Kategori (Super Admin Only)
        Route::resource('categories', CategoryController::class)->middleware(EnsureUserIsSuperAdmin::class);
        
        // Manajemen Produk (Seller/Admin)
        Route::resource('products', ProductController::class);
        Route::post('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])
            ->name('products.toggle-active');

        // Manajemen User (Super Admin Only)
        Route::resource('users', UserController::class)->middleware(EnsureUserIsSuperAdmin::class);

        // Manajemen Metode Pengiriman
        Route::resource('shipping-methods', ShippingMethodController::class)->middleware(EnsureUserIsSuperAdmin::class);

        // Manajemen Transaksi & Laporan
        Route::post('/transactions/{transaction}/{status}', [DashboardController::class, 'updateStatus'])
            ->name('transactions.update-status');
        Route::delete('/transactions/{transaction}/delete', [TransactionController::class, 'destroy'])
            ->name('transactions.destroy');
        Route::get('/transactions/report', [TransactionController::class, 'generateReport'])
            ->name('transactions.report');
    });

require __DIR__.'/auth.php';