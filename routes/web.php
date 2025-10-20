<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('product.index');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/products/{product}', [FrontendProductController::class, 'show'])->name('product.show');
    Route::get('/products',[FrontendProductController::class, 'index'])->name('product.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
    Route::post('/checkout',[TransactionController::class, 'checkout'])->name('checkout.process');
    Route::get('/checkout',[TransactionController::class,'checkoutPage'])->name('checkout.page');


Route::patch('/cart/{cart}/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/{cart}/remove', [CartController::class, 'removeItem'])->name('cart.remove');


    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'generateInvoice'])
    ->name('transactions.invoice')
    ->middleware('auth');
    // Route untuk cetak invoice user
Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'printInvoice'])
    ->middleware('auth')
    ->name('transactions.print-invoice');

    Route::get('/checkout/success/{id}', function ($id) {
    $transaction = \App\Models\Transaction::findOrFail($id);
    return view('frontend.checkout-success', compact('transaction'));})->name('checkout.success');

});
Route::middleware(['auth', EnsureUserIsAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::post('/products/{product}/toggle-active', [ProductController::class, 'toggleActive'])->name('products.toggle-active');
            }); 
        Route::post('/transactions/{transaction}/{status}', [DashboardController::class, 'updateStatus'])
             ->name('transactions.update-status');
        Route::get('/transactions/report', [TransactionController::class, 'generateReport'])
    ->name('transactions.report');
    Route::delete('/transactions/{transaction}/delete', [TransactionController::class, 'destroy'])
    ->name('transactions.destroy');

require __DIR__.'/auth.php';
