<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\MyTravelOrderController;
use App\Http\Controllers\RoleManagementController;
use App\Http\Controllers\TravelOrderStatusController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\NotificationsController;

// Root route - redirects to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return \Illuminate\Support\Facades\Auth::check() 
        ? redirect()->route('dashboard')
        : view('login');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Travel Order Routes
    Route::resource('travel-orders', TravelOrderController::class);
    
    // Additional custom routes for travel orders
    Route::prefix('travel-order')->group(function () {
        Route::get('/my-orders', [MyTravelOrderController::class, 'index'])->name('my-travel-orders');
        Route::get('/all-orders', [TravelOrderController::class, 'index'])->name('all-travel-orders');

        Route::get('/role-management', [RoleManagementController::class, 'index'])->name('role-management');
        Route::post('/role-management/{user}/update-role', [RoleManagementController::class, 'updateRole'])->name('role-management.update-role');
    });
    
    // Alias for the show route to match both singular and plural forms
    Route::get('travel-order/{travel_order}', [TravelOrderController::class, 'show'])->name('travel-order.show');
    
    // Status Management Routes
    Route::prefix('status-management')->group(function () {
        Route::get('/', [TravelOrderStatusController::class, 'index'])->name('status-management.index');
        Route::post('/', [TravelOrderStatusController::class, 'store'])->name('status-management.store');
        Route::put('/{status}', [TravelOrderStatusController::class, 'update'])->name('status-management.update');
        Route::delete('/{status}', [TravelOrderStatusController::class, 'destroy'])->name('status-management.destroy');
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/travel-volume', [\App\Http\Controllers\ReportController::class, 'travelVolume'])->name('reports.travel-volume');
    Route::get('/approval-metrics', [\App\Http\Controllers\ReportController::class, 'approvalMetrics'])->name('reports.approval-metrics');
    Route::get('/employee-travel', [\App\Http\Controllers\ReportController::class, 'employeeTravelPatterns'])->name('reports.employee-travel');
    Route::get('/department', [\App\Http\Controllers\ReportController::class, 'departmentReports'])->name('reports.department');
    
    // Reports Routes
    Route::prefix('reports')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('/travel-volume', [\App\Http\Controllers\ReportController::class, 'travelVolume'])->name('reports.travel-volume');
        Route::get('/approval-metrics', [\App\Http\Controllers\ReportController::class, 'approvalMetrics'])->name('reports.approval-metrics');
        Route::get('/employee-travel', [\App\Http\Controllers\ReportController::class, 'employeeTravelPatterns'])->name('reports.employee-travel');
        Route::get('/department', [\App\Http\Controllers\ReportController::class, 'departmentReports'])->name('reports.department');
    });
});

require __DIR__.'/auth.php';
