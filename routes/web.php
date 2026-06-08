<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| web.php — entry point, delegates to domain route files
|--------------------------------------------------------------------------
|
| Keep this file thin. Domain route files are in routes/ alongside this one.
| Register them in bootstrap/app.php (Laravel 11) or RouteServiceProvider.
|
*/

Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::get('/health', fn () => response()->json(['status' => 'ok']))->name('health');

require __DIR__ . '/auth.php';
require __DIR__ . '/shared.php';
require __DIR__ . '/buyer.php';
require __DIR__ . '/vendor.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/verifier.php';