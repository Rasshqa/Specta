<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GatekeeperController;
use App\Http\Controllers\InfoCenterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SocialiteController;
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
    $ttl = 3600; // 1 hour caching
    $merchandises   = cache()->remember('home_merch', $ttl, fn() => Merchandise::all());
    $eskuls         = cache()->remember('home_eskuls', $ttl, fn() => EskulProfile::active()->get());
    $winners        = cache()->remember('home_winners', $ttl, fn() => Winner::active()->get());
    $timelines      = cache()->remember('home_timelines', $ttl, fn() => Timeline::orderBy('year', 'desc')->get());
    $docsPreviews   = cache()->remember('home_docs', $ttl, fn() => Documentation::active()->latest()->take(6)->get());
    $announcements  = cache()->remember('home_announcements', $ttl, fn() => \App\Models\Information::where('is_active', true)->latest()->get());
    // Cached Ticket Quota Indicator (30 seconds)
    $quotaData = cache()->remember('ticket_quota', 30, function () {
        $totalCapacity = 1500;
        $sold          = \App\Models\Transaction::where('status', 'SUCCESS')->sum('quantity');
        $remaining     = max(0, $totalCapacity - $sold);
        $percentage    = ($sold / $totalCapacity) * 100;

        return (object)[
            'capacity'   => $totalCapacity,
            'sold'       => $sold,
            'remaining'  => $remaining,
            'percentage' => $percentage,
        ];
    });

    return view('welcome', compact('merchandises', 'eskuls', 'winners', 'timelines', 'docsPreviews', 'announcements', 'quotaData'));
})->name('home');

Route::get('/merch', [MerchController::class, 'index'])->name('merch.index');
Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::middleware(['throttle:auth_strict'])->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── Google OAuth (Socialite) ─────────────────────────────────────────────────
Route::middleware(['throttle:auth_strict'])->group(function () {
    Route::get('/auth/google',          [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// ─── Ticket Buying Flow & Dashboard (Protected - requires login) ───────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/tickets',    [\App\Http\Controllers\TicketOrderController::class, 'index'])->name('tickets.index');
    Route::post('/checkout',  [\App\Http\Controllers\TicketOrderController::class, 'checkout'])->name('ticket.checkout');
    Route::get('/my-tickets', [\App\Http\Controllers\UserDashboardController::class, 'myTickets'])->name('user.dashboard');
});

// ─── Payment Pages (Public - accessible via invoice link) ─────────────────────
Route::get('/payment/{invoice}',        [PaymentController::class, 'show'])->name('payment.show');
Route::get('/payment/{invoice}/status', [PaymentController::class, 'status'])->name('payment.status');
Route::post('/payment/{invoice}/proof', [\App\Http\Controllers\TicketOrderController::class, 'uploadProof'])->name('payment.proof.upload');

// ─── Secure E-Ticket Download ─────────────────────────────────────────────────
Route::get('/ticket/download/{token}', [\App\Http\Controllers\TicketOrderController::class, 'downloadTicket'])->name('ticket.download');

// ─── Gatekeeper / QR Scanner (Admin Only) ────────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/gatekeeper',      [GatekeeperController::class, 'index'])->name('gatekeeper.index');
    Route::post('/gatekeeper/scan',[GatekeeperController::class, 'scan'])->name('gatekeeper.scan');
});

// ─── Admin Panel (Protected) ─────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard',    [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::post('/transaction/{invoice}/approve', [\App\Http\Controllers\Admin\TransactionApprovalController::class, 'approve'])->name('transaction.approve');
    Route::post('/transaction/{invoice}/reject',  [\App\Http\Controllers\Admin\TransactionApprovalController::class, 'reject'])->name('transaction.reject');
    Route::post('/scan',        [AdminController::class, 'scanQr'])->name('scan');
    Route::get('/merchandises', [AdminController::class, 'merchandises'])->name('merchandises');
    Route::post('/merchandises',              [AdminController::class, 'merchandiseStore'])->name('merchandises.store');
    Route::post('/merchandises/{merchandise}',[AdminController::class, 'merchandiseUpdate'])->name('merchandises.update');
    Route::delete('/merchandises/{merchandise}',[AdminController::class, 'merchandiseDestroy'])->name('merchandises.destroy');

    // ── Announcements / Informations ─────────────────────────────────────────
    Route::resource('informations', \App\Http\Controllers\Admin\InformationController::class)->except(['show']);

    // ── Info Center Management ────────────────────────────────────────────────
    Route::get('/infocenter/eskul',           [InfoCenterController::class, 'eskulIndex'])->name('infocenter.eskul');
    Route::post('/infocenter/eskul',          [InfoCenterController::class, 'eskulStore'])->name('infocenter.eskul.store');
    Route::post('/infocenter/eskul/{eskul}',  [InfoCenterController::class, 'eskulUpdate'])->name('infocenter.eskul.update');
    Route::delete('/infocenter/eskul/{eskul}',[InfoCenterController::class, 'eskulDestroy'])->name('infocenter.eskul.destroy');

    Route::get('/infocenter/winners',             [InfoCenterController::class, 'winnersIndex'])->name('infocenter.winners');
    Route::post('/infocenter/winners',            [InfoCenterController::class, 'winnersStore'])->name('infocenter.winners.store');
    Route::post('/infocenter/winners/{winner}',   [InfoCenterController::class, 'winnersUpdate'])->name('infocenter.winners.update');
    Route::delete('/infocenter/winners/{winner}', [InfoCenterController::class, 'winnersDestroy'])->name('infocenter.winners.destroy');

    Route::get('/infocenter/docs',                [InfoCenterController::class, 'docsIndex'])->name('infocenter.docs');
    Route::post('/infocenter/docs',               [InfoCenterController::class, 'docsStore'])->name('infocenter.docs.store');
    Route::delete('/infocenter/docs/{documentation}',[InfoCenterController::class, 'docsDestroy'])->name('infocenter.docs.destroy');
});
