<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| auth.php — Fortify handles /login /register /logout automatically.
| We add only what Fortify doesn't cover.
|--------------------------------------------------------------------------
*/

// Email verification notice (Fortify provides /email/verify but not the named route view)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn() => view('pages.auth.verify-email'))
        ->name('verification.notice');
});