<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    CategoryController,
    TransactionController,
    RestockOrderController,
    HomeController,
    ReportController,
    UserController
};

require __DIR__.'/auth.php';

// === HOME (Bisa diakses publik) ===
Route::get('/', fn() => view('welcome'))->name('home');

// === GROUP PROTEKSI PENUH (Login + Wajib Aktif) ===
// Middleware 'is_active' akan menendang user yang login tapi is_active = 0
Route::middleware(['auth', 'is_active'])->group(function () {

    // Redirect Dashboard Pintar
    Route::get('/dashboard', function () {
        return redirect()->route(auth()->user()->getDashboardRoute());
    })->name('dashboard');

    // === ADMIN ===
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

            // User Management
            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            // Laporan
            Route::get('/reports', [ReportController::class, 'index'])->name('reports');
            Route::get('/transactions', [TransactionController::class, 'adminIndex'])->name('transactions.index');
            Route::get('/transactions/{transaction}', [TransactionController::class, 'adminShow'])->name('transactions.show');
            Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
        });

    // === MANAGER ===
    Route::middleware(['role:manager'])
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('manager.dashboard'))->name('dashboard');
            Route::get('/reports', fn() => view('manager.reports'))->name('reports');

            // Transaksi (Approval)
            Route::get('/transactions', [TransactionController::class, 'managerIndex'])->name('transactions.index');
            Route::get('/transactions/{transaction}', [TransactionController::class, 'managerShow'])->name('transactions.show');
            Route::patch('/transactions/{transaction}/approve', [TransactionController::class, 'approve'])->name('transactions.approve');
            Route::patch('/transactions/{transaction}/reject', [TransactionController::class, 'reject'])->name('transactions.reject');
        });

    // === SHARED ADMIN & MANAGER ===
    Route::middleware(['role:admin,manager'])
        ->group(function () {
            Route::resource('products', ProductController::class);
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('restock-orders', RestockOrderController::class)->except(['destroy']);

            // Route Khusus: Manager Terima Barang
            Route::post('/restock-orders/{restockOrder}/receive', [RestockOrderController::class, 'receive'])
                ->name('restock-orders.receive');
        });

    // === STAFF ===
    Route::middleware(['role:staff'])
        ->prefix('staff')
        ->name('staff.')
        ->group(function () {
            Route::get('/dashboard', fn() => view('staff.dashboard'))->name('dashboard');
            Route::get('/stock', [ProductController::class, 'index'])->name('stock');

            // Transaksi (Input)
            Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
            Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
            Route::get('/transactions', [TransactionController::class, 'staffIndex'])->name('transactions.index');
            Route::get('/transactions/{transaction}', [TransactionController::class, 'staffShow'])->name('transactions.show');
        });

    // === SUPPLIER ===
    Route::middleware(['role:supplier'])
        ->prefix('supplier')
        ->group(function () {
            Route::get('/dashboard', [HomeController::class, 'supplierDashboard'])->name('supplier.dashboard');

            // Restock Order (Sisi Supplier)
            Route::get('/restock-orders', [RestockOrderController::class, 'supplierIndex'])->name('supplier.restock-orders.index');
            Route::get('/restock-orders/{restockOrder}', [RestockOrderController::class, 'supplierShow'])->name('supplier.restock-orders.show');

            Route::post('/restock-orders/{restockOrder}/confirm', [RestockOrderController::class, 'supplierConfirm'])
                 ->name('restock-orders.supplier-confirm');
        });

});
Route::get('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout.get');
