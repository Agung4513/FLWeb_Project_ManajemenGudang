<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RestockOrderController;
use App\Http\Controllers\HomeController;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return redirect()->route(auth()->user()->getDashboardRoute());
})->middleware('auth')->name('dashboard');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('/reports', fn() => view('admin.reports'))->name('reports');

        Route::resource('products', ProductController::class)->except(['destroy']);
        Route::resource('categories', CategoryController::class)->except(['show', 'destroy']);
    });

Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');
        Route::get('/reports', fn() => view('manager.reports'))->name('reports');
    });

Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('staff.dashboard'))->name('dashboard');
        Route::get('/stock', fn() => view('staff.stock'))->name('stock');
    });

Route::middleware(['auth', 'role:supplier'])
    ->prefix('supplier')
    ->name('supplier.')
    ->group(function () {
        Route::get('/dashboard', [HomeController::class, 'supplierDashboard'])->name('dashboard');

        Route::get('/restock-orders', [RestockOrderController::class, 'supplierIndex'])
             ->name('restock-orders.index');

        Route::get('/restock-orders/{restockOrder}', [RestockOrderController::class, 'supplierShow'])
             ->name('restock-orders.show');

        Route::post('/restock-orders/{restockOrder}/confirm', [RestockOrderController::class, 'supplierConfirm'])
             ->name('restock-orders.supplier-confirm');
    });

Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::get('/restock-orders/create', [RestockOrderController::class, 'create'])
         ->name('restock-orders.create');
    Route::post('/restock-orders', [RestockOrderController::class, 'store'])
         ->name('restock-orders.store');

    Route::get('/restock-orders', [RestockOrderController::class, 'index'])
         ->name('restock-orders.index');

    Route::get('/restock-orders/{restockOrder}', [RestockOrderController::class, 'show'])
         ->name('restock-orders.show');

    Route::post('/restock-orders/{restockOrder}/receive', [RestockOrderController::class, 'receive'])
         ->name('restock-orders.receive');
});

Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('transactions', TransactionController::class)
         ->except(['edit', 'update', 'destroy']);
});
