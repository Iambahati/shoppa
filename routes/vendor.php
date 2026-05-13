<?php

use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ListingController;
use App\Http\Controllers\Vendor\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| vendor.php — seller-facing routes (prefix: /vendor)
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'verified'])->group(function () {

    // Any authenticated user can apply to become a vendor
    Route::get('/apply',  [ApplicationController::class, 'create'])->name('apply');
    Route::post('/apply', [ApplicationController::class, 'store'])->name('apply.store');

    // Below here requires the Vendor role
    Route::middleware('role:Vendor')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::prefix('listings')->name('listings.')->group(function () {
            Route::get('/',              [ListingController::class, 'index'])->name('index');
            Route::get('/create',        [ListingController::class, 'create'])->name('create');
            Route::post('/',             [ListingController::class, 'store'])->name('store');
            Route::get('/{product}',     [ListingController::class, 'show'])->name('show');
            Route::get('/{product}/edit',[ListingController::class, 'edit'])->name('edit');
            Route::put('/{product}',     [ListingController::class, 'update'])->name('update');
            Route::delete('/{product}',  [ListingController::class, 'destroy'])->name('destroy');
        });

    });

});