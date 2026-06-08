<?php

use App\Http\Controllers\Shared\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| shared.php — routes available to every authenticated role
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('shared.profile');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('shared.profile.update');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('shared.profile.password');

});
