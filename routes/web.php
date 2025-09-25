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
use App\Http\Controllers\SignatureManagementController;

// Root route - redirects to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    return \Illuminate\Support\Facades\Auth::check() 
        ? redirect()->route('dashboard')
        : view('login');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Protected Routes - Require Authentication
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Signature Routes
    Route::prefix('signature')->name('signature.')->group(function() {
        Route::get('/', [\App\Http\Controllers\SignatureController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\SignatureController::class, 'store'])->name('store');
        Route::post('/clear', [\App\Http\Controllers\SignatureController::class, 'clear'])->name('clear');
    });

    // Travel Order Routes
    Route::resource('travel-orders', TravelOrderController::class);
    Route::post('travel-orders/{travel_order}/complete', [TravelOrderController::class, 'complete'])->name('travel-orders.complete');
    Route::post('travel-orders/{travel_order}/update-approvers', [TravelOrderController::class, 'updateApprovers'])->name('travel-orders.update-approvers');

    Route::get('travel-order-print/{id}', [TravelOrderController::class, 'print'])->name('travel-order.print');
    
    // Travel Order Approval/Rejection Routes
    Route::prefix('travel-order')->group(function () {
        // Approve travel order
        Route::post('/{id}/approve', [TravelOrderController::class, 'approve'])->name('travel-orders.approve');
        
        // Reject travel order
        Route::post('/{id}/reject', [TravelOrderController::class, 'reject'])->name('travel-orders.reject');
        
        // Update status (for existing functionality)
        Route::post('/{id}/status', [TravelOrderController::class, 'updateStatus'])->name('travel-orders.status');
    });
    
    // Additional custom routes for travel orders
    Route::prefix('travel-order')->group(function () {
        Route::get('/my-orders', [MyTravelOrderController::class, 'index'])->name('my-orders');
        Route::post('/{id}/status', [TravelOrderController::class, 'updateStatus'])->name('travel-orders.update-status');
        
        // Approval workflow routes
        Route::get('/for-approval', [TravelOrderController::class, 'forApproval'])->name('for-approval');
        Route::get('/for-recommendation', [TravelOrderController::class, 'forRecommendation'])->name('for-recommendation');
        Route::get('/all-orders', [TravelOrderController::class, 'index'])->name('admin.index');
        
        // Travel Orders History
        Route::get('/history', [TravelOrderController::class, 'history'])->name('history');
    });

    // Role Management Routes
    Route::prefix('role-management')->name('role-management.')->group(function () {
        Route::get('/', [RoleManagementController::class, 'index'])->name('index');
        Route::post('/{user}/update-role', [RoleManagementController::class, 'updateRole'])->name('update-role');
    });
    
    // Signature Management (admin UI, controller enforces admin)
    Route::prefix('signature-management')->name('signature-management.')->group(function () {
        Route::get('/', [SignatureManagementController::class, 'index'])->name('index');
        Route::post('/{employee}/reset', [SignatureManagementController::class, 'reset'])->name('reset');
    });
    
    // Status Management Routes
    Route::prefix('status-management')->group(function () {
        Route::get('/', [TravelOrderStatusController::class, 'index'])->name('status-management.index');
        Route::post('/', [TravelOrderStatusController::class, 'store'])->name('status-management.store');
        Route::put('/{status}', [TravelOrderStatusController::class, 'update'])->name('status-management.update');
        Route::delete('/{status}', [TravelOrderStatusController::class, 'destroy'])->name('status-management.destroy');
        Route::post('/travel-orders/{id}/reset', [TravelOrderStatusController::class, 'reset'])->name('status-management.reset');
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
