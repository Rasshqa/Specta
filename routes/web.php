<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GatekeeperController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\Merchandise;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', function () {
    $merchandises = Merchandise::all();
    return view('welcome', compact('merchandises'));
});

// ─── Authentication ───────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Ticket Buying Flow (Public) ──────────────────────────────────────────────
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::post('/checkout', [TicketController::class, 'checkout'])->name('ticket.checkout');

// ─── Payment Pages (Public - accessible via invoice link) ─────────────────────
Route::get('/payment/{invoice}', [PaymentController::class, 'show'])->name('payment.show');
Route::get('/payment/{invoice}/status', [PaymentController::class, 'status'])->name('payment.status');

// ─── Secure E-Ticket Download ─────────────────────────────────────────────────
Route::get('/ticket/download/{token}', [TicketController::class, 'downloadTicket'])->name('ticket.download');

// ─── Gatekeeper / QR Scanner ─────────────────────────────────────────────────
Route::get('/gatekeeper', [GatekeeperController::class, 'index'])->name('gatekeeper.index');
Route::post('/gatekeeper/scan', [GatekeeperController::class, 'scan'])->name('gatekeeper.scan');

// ─── Admin Panel (Protected) ─────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::post('/transaction/{invoice}/confirm', [AdminController::class, 'confirmTransaction'])->name('transaction.confirm');
    Route::post('/transaction/{invoice}/expire', [AdminController::class, 'expireTransaction'])->name('transaction.expire');
    Route::get('/merchandises', [AdminController::class, 'merchandises'])->name('merchandises');
});

