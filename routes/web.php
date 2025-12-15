<?php

use App\Http\Controllers\Apps\CategoryController;
use App\Http\Controllers\Apps\DashboardController;
use App\Http\Controllers\Apps\ProductController;
use App\Http\Controllers\Apps\LocationController;
use App\Http\Controllers\Apps\LocationTypeController;
use App\Http\Controllers\Apps\SupplierController;
use App\Http\Controllers\Apps\UoMController;
use App\Http\Controllers\Apps\WarehouseController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/products/counts', [ProductController::class, 'counts'])->name('products.counts');
    
    Route::post('/suppliers/datatable', [SupplierController::class, 'datatable'])->name('suppliers.datatable');
    Route::post('/categories/datatable', [CategoryController::class, 'datatable'])->name('categories.datatable');
    Route::post('/products/datatable', [ProductController::class, 'datatable'])->name('products.datatable');
    Route::post('/uom/datatable', [UoMController::class, 'datatable'])->name('uom.datatable');
    Route::post('/warehouses/datatable', [WarehouseController::class, 'datatable'])->name('warehouses.datatable');
    Route::post('/location-types/datatable', [LocationTypeController::class, 'datatable'])->name('location-types.datatable');
    Route::post('/locations/datatable', [LocationController::class, 'datatable'])->name('locations.datatable');


    Route::post('/categories/select', [CategoryController::class, 'select'])->name('categories.select');
    Route::post('/suppliers/select', [SupplierController::class, 'select'])->name('suppliers.select');
    Route::post('/uom/select', [UoMController::class, 'select'])->name('uom.select');
    Route::post('/warehouses/select', [WarehouseController::class, 'select'])->name('warehouses.select');
    Route::post('/location-types/select', [LocationTypeController::class, 'select'])->name('location-types.select');
    Route::get('/locations/select', [LocationController::class, 'select'])->name('locations.select');

    Route::resource('dashboard', DashboardController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('uom', UoMController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('location-types', LocationTypeController::class);
    Route::resource('locations', LocationController::class);
    
});

Route::middleware(['auth'])->prefix('user-management')->name('user-management.')->group(function () {
    Route::resource('users', RegisterController::class);
    Route::post('/users/datatable', [RegisterController::class, 'datatable'])->name('users.datatable');
});

