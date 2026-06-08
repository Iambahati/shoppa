<?php

use App\Http\Controllers\Buyer\BrowseController;
use App\Http\Controllers\Buyer\DashboardController;
use App\Http\Controllers\Buyer\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('buyer.dashboard');

    Route::get('/browse', [BrowseController::class, 'index'])
        ->name('buyer.browse');

    Route::get('/browse/{product}', [BrowseController::class, 'show'])
        ->name('buyer.product.show');

    Route::prefix('orders')->name('buyer.orders.')->group(function () {
        Route::get('/',                       [OrderController::class, 'index'])->name('index');
        Route::post('/',                      [OrderController::class, 'store'])->name('store');
        Route::get('/{order}',                [OrderController::class, 'show'])->name('show');
        Route::post('/{order}/cancel',        [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{order}/confirm',       [OrderController::class, 'confirmReceipt'])->name('confirm');
        Route::post('/{order}/dispute',       [OrderController::class, 'dispute'])->name('dispute');
    });

});
