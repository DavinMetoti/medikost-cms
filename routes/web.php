<?php

use App\Http\Controllers\Apps\DashboardController;
use App\Http\Controllers\Apps\ProductController;
use App\Http\Controllers\Apps\ProductDetailController;
use App\Http\Controllers\Apps\BookingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Root route: arahkan sesuai status login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('app.dashboard.index')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->get('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('app')->name('app.')->group(function () {
    Route::get('/products/counts', [ProductController::class, 'counts'])->middleware('can:view kos')->name('products.counts');
    Route::post('/products/search', [ProductController::class, 'search'])->middleware('can:view kos')->name('products.search');
    
    Route::post('/products/datatable', [ProductController::class, 'datatable'])->middleware('can:view kos')->name('products.datatable');
    Route::resource('dashboard', DashboardController::class)->middleware('can:view landing page');
    Route::resource('products', ProductController::class)->middleware('can:view kos');
    Route::resource('product-details', ProductDetailController::class)->middleware('can:view rooms');
    Route::get('/product-details/counts', [ProductDetailController::class, 'counts'])->middleware('can:view rooms')->name('product-details.counts');
    Route::post('/product-details/datatable', [ProductDetailController::class, 'datatable'])->middleware('can:view rooms')->name('product-details.datatable');
    
    Route::resource('bookings', BookingController::class)->middleware('can:view rooms');
    Route::post('/bookings/datatable', [BookingController::class, 'datatable'])->middleware('can:view rooms')->name('bookings.datatable');
});

Route::middleware(['auth'])->prefix('user-management')->name('user-management.')->group(function () {
    Route::resource('users', RegisterController::class)->middleware('can:view users');
    Route::post('/users/datatable', [RegisterController::class, 'datatable'])->middleware('can:view users')->name('users.datatable');
});

