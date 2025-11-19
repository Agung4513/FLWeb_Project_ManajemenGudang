<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('/reports', fn() => view('admin.reports'))->name('reports');
        Route::resource('products', \App\Http\Controllers\ProductController::class)->except(['destroy']);
        Route::resource('categories', \App\Http\Controllers\CategoryController::class)->except(['show','destroy']);
    });

    Route::middleware(['role:manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');
        Route::get('/reports', fn() => view('manager.reports'))->name('reports');
        Route::resource('transactions', \App\Http\Controllers\TransactionController::class);
        Route::resource('restock-orders', \App\Http\Controllers\RestockOrderController::class);
    });

    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', fn() => view('staff.dashboard'))->name('dashboard');
        Route::get('/stock', fn() => view('staff.stock'))->name('stock');
        Route::resource('transactions', \App\Http\Controllers\TransactionController::class)
            ->only(['index', 'create', 'store', 'show']);
    });

    Route::middleware(['role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/dashboard', fn() => view('supplier.dashboard'))->name('dashboard');
        Route::get('/orders', fn() => view('supplier.orders'))->name('orders');
        Route::resource('restock-orders', \App\Http\Controllers\RestockOrderController::class)
            ->only(['index', 'show']);
    });
    Route::middleware(['auth', 'role:admin,manager'])
        ->group(function () {

        Route::resource('products', ProductController::class);

        Route::resource('categories', CategoryController::class);
    });
});
