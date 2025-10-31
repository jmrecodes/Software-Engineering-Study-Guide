<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/cart/add/{product}', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [OrderController::class, 'showCart'])->name('cart.show');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

Route::view('/login', 'auth.login')->name('login');
