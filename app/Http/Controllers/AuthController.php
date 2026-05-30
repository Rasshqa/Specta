<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->isGatekeeper()) {
                return redirect()->route('gatekeeper.index');
            }
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Show the registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::query()->create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // Default is normal buyer
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Akun berhasil dibuat.');
    }

    /**
     * Handle login attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isGatekeeper()) {
                return redirect()->intended(route('gatekeeper.index'));
            }

            // Normal buyer redirects to home or intended url
            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil keluar.');
    }
}

