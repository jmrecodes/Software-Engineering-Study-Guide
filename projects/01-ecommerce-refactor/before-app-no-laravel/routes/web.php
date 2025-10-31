<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', [OrderController::class, 'index'])->name('products.index');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::post('/cart/add/{product}', [OrderController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [OrderController::class, 'showCart'])->name('cart.show');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.process');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
