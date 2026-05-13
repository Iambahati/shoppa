<?php

use App\Http\Controllers\Verifier\InspectionController;
use App\Http\Controllers\Verifier\QueueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| verifier.php — device inspection routes (prefix: /verifier)
|--------------------------------------------------------------------------
*/

Route::prefix('verifier')->name('verifier.')->middleware([
    'auth',
    'verified',
    'role:Super Admin,Admin,Verifier',
])->group(function () {

    Route::get('/queue', [QueueController::class, 'index'])
        ->name('queue');

    Route::prefix('inspections')->name('inspections.')->group(function () {
        Route::get('/{product}',        [InspectionController::class, 'show'])->name('show');
        Route::post('/{product}',       [InspectionController::class, 'store'])->name('store');
        Route::post('/{product}/certify', [InspectionController::class, 'certify'])->name('certify');
        Route::post('/{product}/reject',  [InspectionController::class, 'reject'])->name('reject');
    });

    // Public IMEI/serial lookup — no auth needed, registered separately in web.php
});

// Public verify endpoint — no auth or role gate
Route::get('/verify/{identifier}', fn(string $identifier) => response()->json(['todo' => $identifier]))
    ->name('public.verify');