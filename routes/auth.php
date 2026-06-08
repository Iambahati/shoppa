<?php

use Illuminate\Support\Facades\Route;

// Fortify registers /login /register /logout /forgot-password /reset-password automatically.
// We only add the email verification notice view here.

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('pages.auth.verify-email'))
        ->name('verification.notice');
});
