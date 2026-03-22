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

/* ================= PUBLIC ================= */
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/products', [FrontendProductController::class, 'index'])->name('product.index');
Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('product.show');

/* ================= USER AREA (Login Required) ================= */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('product.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::patch('/cart/{cart}/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cart}/remove', [CartController::class, 'removeItem'])->name('cart.remove');

    Route::get('/checkout', [TransactionController::class,'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout.process');

    // 🔥 MODIFIKASI: Diarahkan ke Controller agar data Transaction ter-load dengan benar
    Route::get('/checkout/success', [TransactionController::class, 'success'])->name('checkout.success');

    // Riwayat Pesanan Customer
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/my-orders/{transaction}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::delete('/my-orders/{transaction}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Nama rute: transactions.print-invoice (Bisa diakses Admin & User)
    Route::get('/transactions/{transaction}/print-invoice', [TransactionController::class, 'printInvoice'])
        ->name('transactions.print-invoice');
});

/* ================= ADMIN AREA ================= */
Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class)->middleware(EnsureUserIsSuperAdmin::class);
        Route::resource('products', ProductController::class);
        Route::resource('users', UserController::class)->middleware(EnsureUserIsSuperAdmin::class);

        Route::post('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])
            ->name('products.toggle-active');

        Route::resource('shipping-methods', ShippingMethodController::class)->middleware(EnsureUserIsSuperAdmin::class);

        Route::post('/transactions/{transaction}/{status}', [DashboardController::class, 'updateStatus'])
            ->name('transactions.update-status');

        Route::delete('/transactions/{transaction}/delete', [TransactionController::class, 'destroy'])
            ->name('transactions.destroy');

        Route::get('/transactions/report', [TransactionController::class, 'generateReport'])
            ->name('transactions.report');
    });

require __DIR__.'/auth.php';