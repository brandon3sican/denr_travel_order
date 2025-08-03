<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');
});

// Logout Route
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Protected Routes - Require Authentication
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Travel Order Routes
    Route::prefix('travel-order')->group(function () {
        Route::get('/create', function () {
            return view('travel-order.create-travel-order');
        })->name('create-travel-order');

        Route::get('/my-orders', function () {
            return view('travel-order.my-travel-orders');
        })->name('my-travel-orders');

        Route::get('/role-management', function () {
            return view('role-management.role-management');
        })->name('role-management');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
