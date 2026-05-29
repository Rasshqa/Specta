<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;

use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\GatekeeperController;

// Public API Routes
Route::post('/login', [ApiAuthController::class, 'login']);

// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
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
});
