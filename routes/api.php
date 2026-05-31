<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;

use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\GatekeeperController;
use App\Http\Controllers\Api\AdminTransactionApiController;

// Public API Routes
Route::middleware(['throttle:60,1'])->post('/login', [ApiAuthController::class, 'login']);

// Serve images via API to ensure CORS headers are attached (useful for local Flutter web dev)
Route::get('/images/proofs/{filename}', function ($filename) {
    // SECURITY: Prevent Directory Traversal (LFI) by using basename
    $safeFilename = basename($filename);
    $path = storage_path('app/public/proofs/' . $safeFilename);
    if (!file_exists($path)) {
        return response()->json(['error' => 'Image not found'], 404);
    }
    
    $mime = 'image/jpeg';
    if (str_ends_with(strtolower($filename), '.png')) $mime = 'image/png';
    if (str_ends_with(strtolower($filename), '.jpg')) $mime = 'image/jpeg';
    if (str_ends_with(strtolower($filename), '.jfif')) $mime = 'image/jpeg';
    
    return response()->file($path, ['Content-Type' => $mime]);
});

// Protected API Routes
Route::middleware(['auth:sanctum', 'admin', 'throttle:60,1'])->group(function () {
    // Return current authenticated user
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data'    => $request->user()
        ]);
    });

    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // Mobile Dashboard Stats
    Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);

    // Gatekeeper Scanner Endpoint
    Route::post('/gatekeeper/scan', [GatekeeperController::class, 'scan']);

    // Admin transaction management (mobile)
    Route::get('/admin/transactions/pending', [AdminTransactionApiController::class, 'pending']);
    Route::post('/admin/transaction/{invoice}/approve', [AdminTransactionApiController::class, 'approve']);
    Route::post('/admin/transaction/{invoice}/reject', [AdminTransactionApiController::class, 'reject']);
    Route::post('/admin/transaction/manual', [AdminTransactionApiController::class, 'storeManual']);
});
