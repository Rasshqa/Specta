<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Velorans – SPECTA REVELIORA</title>
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
<body class="text-slate-100 min-h-screen grid-bg flex items-center justify-center px-4 py-12 relative overflow-x-hidden">

    {{-- Ambient Universe Glows --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    </div>

    <div class="relative w-full max-w-md z-10" x-data="{ showPass: false, showPassConfirm: false }">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8 float flex justify-center">
            <img src="{{ asset('images/logo_specta.png') }}" alt="Specta Logo" class="h-32 object-contain drop-shadow-[0_0_15px_rgba(186,131,255,0.4)]">
        </div>

        {{-- Card --}}
        <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-800/60 rounded-3xl p-8 shadow-2xl glow-purple">

            <h2 class="text-xl font-bold text-slate-100 mb-1">Registrasi</h2>
            <p class="text-slate-500 text-sm mb-6">Buat akun untuk mengamankan tiket Anda.</p>

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                        class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500/30 transition-all text-sm"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Alamat Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@domain.com"
                        required
                        class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500/30 transition-all text-sm"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Password</label>
                    <div class="relative">
                        <input
                            id="password"
                            :type="showPass ? 'text' : 'password'"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 pr-12 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500/30 transition-all text-sm"
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
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Konfirmasi Password</label>
                    <div class="relative">
                        <input
                            id="password_confirmation"
                            :type="showPassConfirm ? 'text' : 'password'"
                            name="password_confirmation"
                            placeholder="Ulangi password Anda"
                            required
                            class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 pr-12 text-slate-200 placeholder-slate-600 focus:outline-none focus:border-purple-500/80 focus:ring-1 focus:ring-purple-500/30 transition-all text-sm"
                        >
                        <button
                            type="button"
                            @click="showPassConfirm = !showPassConfirm"
                            class="absolute inset-y-0 right-4 flex items-center text-slate-500 hover:text-slate-300 transition-colors"
                            tabindex="-1"
                        >
                            <span x-show="!showPassConfirm" class="text-sm"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 9a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5a5 5 0 0 1 5-5a5 5 0 0 1 5 5a5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5"/></svg></span>
                            <span x-show="showPassConfirm" class="text-sm"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3zm-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28l.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5c1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22L21 20.73L3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75c-1.73-4.39-6-7.5-11-7.5c-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7"/></svg></span>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-purple-900/30 hover:shadow-purple-500/20 hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-wide mt-6 cursor-pointer"
                >
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 text-center text-sm">
                <span class="text-slate-500">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="text-purple-400 hover:text-cyan-400 font-bold ml-1 transition-colors">Sign In</a>
            </div>
        </div>

        <p class="text-center text-slate-600 text-xs mt-6">
            &copy; {{ date('Y') }} SPECTA XXI · SMAN 1 Cianjur
        </p>
    </div>

</body>
</html>
