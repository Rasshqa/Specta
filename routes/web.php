<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GatekeeperController;
use App\Http\Controllers\InfoCenterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Models\Merchandise;
use App\Models\EskulProfile;
use App\Models\Winner;
use App\Models\Timeline;
use App\Models\Documentation;
use App\Http\Controllers\MerchController;
use App\Http\Controllers\DocsController;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', function () {
    $merchandises   = Merchandise::all();
    $eskuls         = EskulProfile::active()->get();
    $winners        = Winner::active()->get();
    $timelines      = Timeline::orderBy('year', 'desc')->get();
    $docsPreviews   = Documentation::active()->latest()->take(6)->get();
    $announcements  = \App\Models\Information::where('is_active', true)->latest()->get();
    
    return view('welcome', compact('merchandises', 'eskuls', 'winners', 'timelines', 'docsPreviews', 'announcements'));
})->name('home');

Route::get('/merch', [MerchController::class, 'index'])->name('merch.index');
Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Ticket Buying Flow (Protected - requires login) ─────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/checkout', [TicketController::class, 'checkout'])->name('ticket.checkout');
});

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
    Route::post('/scan', [AdminController::class, 'scanQr'])->name('scan');
    Route::get('/merchandises', [AdminController::class, 'merchandises'])->name('merchandises');
    Route::post('/merchandises', [AdminController::class, 'merchandiseStore'])->name('merchandises.store');
    Route::post('/merchandises/{merchandise}', [AdminController::class, 'merchandiseUpdate'])->name('merchandises.update');
    Route::delete('/merchandises/{merchandise}', [AdminController::class, 'merchandiseDestroy'])->name('merchandises.destroy');

    // ── Announcements / Informations ───────────────────────────────────────────
    Route::resource('informations', \App\Http\Controllers\Admin\InformationController::class)->except(['show']);

    // ── Info Center Management ────────────────────────────────────────────────
    Route::get('/infocenter/eskul', [InfoCenterController::class, 'eskulIndex'])->name('infocenter.eskul');
    Route::post('/infocenter/eskul', [InfoCenterController::class, 'eskulStore'])->name('infocenter.eskul.store');
    Route::post('/infocenter/eskul/{eskul}', [InfoCenterController::class, 'eskulUpdate'])->name('infocenter.eskul.update');
    Route::delete('/infocenter/eskul/{eskul}', [InfoCenterController::class, 'eskulDestroy'])->name('infocenter.eskul.destroy');

    Route::get('/infocenter/winners', [InfoCenterController::class, 'winnersIndex'])->name('infocenter.winners');
    Route::post('/infocenter/winners', [InfoCenterController::class, 'winnersStore'])->name('infocenter.winners.store');
    Route::post('/infocenter/winners/{winner}', [InfoCenterController::class, 'winnersUpdate'])->name('infocenter.winners.update');
    Route::delete('/infocenter/winners/{winner}', [InfoCenterController::class, 'winnersDestroy'])->name('infocenter.winners.destroy');

    Route::get('/infocenter/docs', [InfoCenterController::class, 'docsIndex'])->name('infocenter.docs');
    Route::post('/infocenter/docs', [InfoCenterController::class, 'docsStore'])->name('infocenter.docs.store');
    Route::delete('/infocenter/docs/{documentation}', [InfoCenterController::class, 'docsDestroy'])->name('infocenter.docs.destroy');
});
