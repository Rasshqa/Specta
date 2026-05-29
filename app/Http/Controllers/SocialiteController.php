<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect pengguna ke halaman login Google OAuth.
     *
     * Opsi 'select_account' memaksa Google selalu menampilkan
     * pemilihan akun, bahkan jika sudah ada sesi aktif.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Tangani callback dari Google setelah pengguna memberi izin.
     *
     * Alur logika:
     *  1. Ambil data pengguna dari Google (try-catch untuk error network/token)
     *  2. Jika email sudah ada di DB:
     *     - Jika google_id kosong → tautkan akun (account linking)
     *     - Jika google_id sudah ada → login langsung
     *  3. Jika email belum ada → buat akun baru
     *  4. Regenerate session (cegah Session Fixation Attack)
     *  5. Login dan redirect berdasarkan role
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

        } catch (Exception $e) {
            // Tangani error: token tidak valid, akses ditolak pengguna, timeout jaringan, dsb.
            Log::warning('Google OAuth callback gagal.', [
                'error'   => $e->getMessage(),
                'ip'      => $request->ip(),
                'session' => $request->session()->getId(),
            ]);

            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // Pastikan Google mengembalikan email yang valid
        if (empty($googleUser->getEmail())) {
            Log::warning('Google OAuth: email kosong diterima.', [
                'google_id' => $googleUser->getId(),
            ]);

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Google Anda tidak memiliki email yang valid.']);
        }

        // Cari user berdasarkan email dari Google
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // ── Skenario A: Email sudah terdaftar di database ─────────────────────
            if (empty($user->google_id)) {
                // Email ada, tapi belum pernah login via Google → tautkan google_id
                $user->update(['google_id' => $googleUser->getId()]);
            }
            // Jika google_id sudah ada → tidak perlu update, lanjut ke login
        } else {
            // ── Skenario B: Email belum ada → Buat akun baru ─────────────────────
            $user = User::create([
                'name'      => $googleUser->getName() ?? 'Pengguna Google',
                'email'     => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                // Generate password acak yang kuat (32 karakter)
                // Password ini tidak bisa digunakan untuk login lokal
                'password'  => bcrypt(Str::random(32)),
                'role'      => 'user',
            ]);
        }

        // ── Hardening: Cegah Session Fixation Attack ──────────────────────────
        // WAJIB dilakukan SEBELUM Auth::login() untuk memastikan sesi baru
        $request->session()->regenerate();

        // Login dengan sesi persisten (remember me)
        Auth::login($user, remember: true);

        // ── Redirect berdasarkan role (konsisten dengan AuthController) ───────
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->isGatekeeper()) {
            return redirect()->intended(route('gatekeeper.index'));
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang, ' . $user->name . '! Login dengan Google berhasil.');
    }
}
