<?php

use Illuminate\Support\Facades\Route;

// The website lives in React now. Laravel only sends visitors there.
Route::get('/', fn () => redirect(config('app.frontend_url')))->name('home');
Route::get('/login', fn () => redirect(config('app.frontend_url') . '/#login'))->name('login');