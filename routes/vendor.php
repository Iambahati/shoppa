<?php

use App\Http\Controllers\Vendor\ApplicationController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\ListingController;
use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->name('vendor.')->middleware(['auth', 'verified'])->group(function () {

    // Any authenticated user can apply
    Route::get('/apply',  [ApplicationController::class, 'create'])->name('apply');
    Route::post('/apply', [ApplicationController::class, 'store'])->name('apply.store');

    // Vendor-role gated
    Route::middleware('role:Vendor')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::prefix('listings')->name('listings.')->group(function () {
            Route::get('/',               [ListingController::class, 'index'])->name('index');
            Route::get('/create',         [ListingController::class, 'create'])->name('create');
            Route::post('/',              [ListingController::class, 'store'])->name('store');
            Route::get('/{product}',      [ListingController::class, 'show'])->name('show');
            Route::get('/{product}/edit', [ListingController::class, 'edit'])->name('edit');
            Route::put('/{product}',      [ListingController::class, 'update'])->name('update');
            Route::delete('/{product}',   [ListingController::class, 'destroy'])->name('destroy');
        });

    });

});
