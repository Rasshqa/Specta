<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In – SPECTA REVELIORA</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #000000;
        }
        .glow-purple {
            box-shadow: rgba(186, 131, 255, 0.2) 0px 0px 40px 0px, rgba(186, 131, 255, 0.05) 0px 0px 80px 0px;
        }
        .grid-bg {
            background-image:
                linear-gradient(rgba(186,131,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(186,131,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
        }
        .float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="text-slate-100 min-h-screen grid-bg flex items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- Ambient Universe Glows --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    </div>

    <div class="relative w-full max-w-md z-10" x-data="{ showPass: false }">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8 float flex justify-center">
            <img src="{{ asset('images/logo_specta.png') }}" alt="Specta Logo" class="h-32 object-contain drop-shadow-[0_0_15px_rgba(186,131,255,0.4)]">
        </div>

        {{-- Card --}}
        <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-800/60 rounded-3xl p-8 shadow-2xl glow-purple">

            <h2 class="text-xl font-bold text-slate-100 mb-1">Selamat Datang</h2>
            <p class="text-slate-500 text-sm mb-8">Masuk untuk melanjutkan pembelian tiket.</p>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        placeholder="contoh@domain.com"
                        required
                        class="w-full bg-slate-800/60 border @error('email') border-red-500/60 @else border-slate-700/60 @enderror rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500/30 transition-all text-sm"
                    >
                    @error('email')
                    <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            class="w-full bg-slate-800/60 border @error('password') border-red-500/60 @else border-slate-700/60 @enderror rounded-xl px-4 py-3 pr-12 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500/30 transition-all text-sm"
                        >
                        <button
                            type="button"
                            @click="showPass = !showPass"
                            class="absolute inset-y-0 right-4 flex items-center text-slate-500 hover:text-slate-300 transition-colors"
                            tabindex="-1"
                        >
                            <span x-show="!showPass" class="text-sm"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5a5 5 0 0 1 5-5a5 5 0 0 1 5 5a5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5"/></svg></span>
                            <span x-show="showPass" class="text-sm"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3zm-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28l.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5c1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22L21 20.73L3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75c-1.73-4.39-6-7.5-11-7.5c-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7"/></svg></span>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-1.5 text-red-400 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input
                        id="remember"
                        type="checkbox"
                        name="remember"
                        class="w-4 h-4 accent-purple-500 rounded bg-slate-900 border-slate-700"
                    >
                    <label for="remember" class="text-sm text-slate-400 select-none cursor-pointer">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    id="btn-login"
                    type="submit"
                    class="w-full bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-purple-900/30 hover:shadow-purple-500/20 hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-wide cursor-pointer"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <span class="text-slate-500">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="text-purple-400 hover:text-cyan-400 font-bold ml-1 transition-colors">Daftar Sekarang</a>
            </div>

            {{-- ── Divider ──────────────────────────────────────────────────────── --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-700/60"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-slate-900/80 px-3 text-slate-500 uppercase tracking-widest">atau</span>
                </div>
            </div>

            {{-- ── Tombol Login dengan Google ───────────────────────────────────── --}}
            <a
                id="btn-google-login"
                href="{{ route('auth.google') }}"
                class="group flex items-center justify-center gap-3 w-full
                       bg-white/5 hover:bg-white/10
                       border border-slate-700/60 hover:border-white/20
                       text-slate-200 hover:text-white
                       font-semibold text-sm
                       py-3 rounded-xl
                       transition-all duration-300
                       hover:-translate-y-0.5 hover:shadow-lg hover:shadow-black/30
                       active:translate-y-0"
            >
                {{-- Google "G" Logo SVG (warna resmi) --}}
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </div>

        <p class="text-center text-slate-600 text-xs mt-6">
            &copy; {{ date('Y') }} SPECTA XXI · SMAN 1 Cianjur
        </p>
    </div>

</body>
</html>
