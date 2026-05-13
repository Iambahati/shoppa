<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DisputeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| admin.php — staff panel routes (prefix: /admin)
| Stacked role middleware: any of these roles may enter /admin.
| Finer per-route permission checks live in the controllers themselves.
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware([
    'auth',
    'verified',
    'role:Super Admin,Admin,Vendor Manager,Customer Service,Content Manager',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Users — Admin / Super Admin only
    Route::resource('users', UserController::class)
        ->middleware('role:Super Admin,Admin');

    // Vendors — Admin / Vendor Manager
    Route::prefix('vendors')->name('vendors.')->middleware('role:Super Admin,Admin,Vendor Manager')
        ->group(function () {
            Route::get('/',                  [VendorController::class, 'index'])->name('index');
            Route::get('/{vendor}',          [VendorController::class, 'show'])->name('show');
            Route::post('/{vendor}/approve', [VendorController::class, 'approve'])->name('approve');
            Route::post('/{vendor}/reject',  [VendorController::class, 'reject'])->name('reject');
        });

    // Products / catalog — Admin / Content Manager
    Route::resource('products', ProductController::class)
        ->middleware('role:Super Admin,Admin,Content Manager');

    // Disputes — Admin / Customer Service
    Route::prefix('disputes')->name('disputes.')->middleware('role:Super Admin,Admin,Customer Service')
        ->group(function () {
            Route::get('/',                    [DisputeController::class, 'index'])->name('index');
            Route::get('/{dispute}',           [DisputeController::class, 'show'])->name('show');
            Route::post('/{dispute}/resolve',  [DisputeController::class, 'resolve'])->name('resolve');
        });

});