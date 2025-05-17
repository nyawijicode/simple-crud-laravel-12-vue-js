<?php

use Illuminate\Support\Facades\Route;

// Route default untuk halaman welcome
Route::get('/', function () {
    return view('welcome');
});

// Penting untuk SPA Authentication dengan Sanctum
// Ini memungkinkan frontend mendapatkan CSRF token
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Fallback route - penting untuk SPA
Route::fallback(function () {
    return view('welcome');
});