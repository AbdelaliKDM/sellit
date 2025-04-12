<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');



// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Password Reset Routes (placeholder)
Route::get('/password/reset', function() {
    return view('auth.passwords.email');
})->name('password.request');

Route::resource('products', \App\Http\Controllers\ProductController::class)->middleware('auth');

// Add these routes to your existing web.php file
Route::get('products/{product}/discounts/create', [App\Http\Controllers\DiscountController::class, 'create'])->name('products.discounts.create');
Route::post('products/{product}/discounts', [App\Http\Controllers\DiscountController::class, 'store'])->name('products.discounts.store');
Route::resource('discounts', App\Http\Controllers\DiscountController::class)->only(['edit', 'update', 'destroy']);

// Customer Routes
Route::resource('customers', CustomerController::class)->middleware('auth');

// POS Routes
Route::get('/pos', [PosController::class, 'index'])->name('pos.index')->middleware('auth');
Route::get('/pos/search-products', [PosController::class, 'searchProducts'])->name('pos.search-products')->middleware('auth');
Route::get('/pos/product-by-barcode', [PosController::class, 'getProductByBarcode'])->name('pos.product-by-barcode')->middleware('auth');
Route::post('/pos/quick-add-product', [PosController::class, 'quickAddProduct'])->name('pos.quick-add-product')->middleware('auth');
Route::post('/pos/process-order', [PosController::class, 'processOrder'])->name('pos.process-order')->middleware('auth');

// Order Routes
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('auth');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth');
Route::put('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status')->middleware('auth');
Route::put('/orders/{order}/update-payment', [OrderController::class, 'updatePayment'])->name('orders.update-payment')->middleware('auth');

// Add these routes to your web.php file
Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

// Account Routes
Route::get('/account', [AccountController::class, 'index'])->name('account.index')->middleware('auth');
Route::put('/account', [AccountController::class, 'update'])->name('account.update')->middleware('auth');
