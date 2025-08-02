<?php

use Illuminate\Support\Facades\Route;

// Login Page
Route::get('/', function () {
    return view('login');
})->name('login');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->name('dashboard');

// Travel Order Routes
Route::prefix('travel-order')->group(function () {
    Route::get('/create', function () {
        return view('travel-order.create-travel-order');
    })->name('create-travel-order');

    Route::get('/my-orders', function () {
        return view('travel-order.my-travel-orders');
    })->name('my-travel-orders');
});

// User Management
Route::get('/user-management', function () {
    return view('user-management.user-management');
})->name('user-management');