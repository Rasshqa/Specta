<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MootaWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Moota Webhook endpoint
Route::post('/webhooks/moota', [MootaWebhookController::class, 'handle'])
    ->middleware('moota.webhook')
    ->name('api.webhooks.moota');
