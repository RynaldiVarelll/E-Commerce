<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingMethodController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\User\OrderController; // 🔥 PASTIKAN INI ADA
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;

/* ================= PUBLIC ================= */
Route::get('/', function () {
    return view('welcome');
})->name('home');

/* ================= USER AREA (Login Required) ================= */
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('product.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/products', [FrontendProductController::class, 'index'])->name('product.index');
    Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('product.show');

    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
    Route::patch('/cart/{cart}/update', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/{cart}/remove', [CartController::class, 'removeItem'])->name('cart.remove');

    Route::get('/checkout', [TransactionController::class,'checkoutPage'])->name('checkout.page');
    Route::post('/checkout', [TransactionController::class, 'checkout'])->name('checkout.process');

    Route::get('/checkout/success/{transaction}', function ($transaction) {
        $transaction = \App\Models\Transaction::findOrFail($transaction);
        return view('frontend.checkout-success', compact('transaction'));
    })->name('checkout.success');

    // 🔥 FITUR BARU: Riwayat Pesanan Customer
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');

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
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);

        Route::post('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])
            ->name('products.toggle-active');

        Route::resource('shipping-methods', ShippingMethodController::class);

        Route::post('/transactions/{transaction}/{status}', [DashboardController::class, 'updateStatus'])
            ->name('transactions.update-status');

        Route::delete('/transactions/{transaction}/delete', [TransactionController::class, 'destroy'])
            ->name('transactions.destroy');

        Route::get('/transactions/report', [TransactionController::class, 'generateReport'])
            ->name('transactions.report');
    });

require __DIR__.'/auth.php';