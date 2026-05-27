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
<body class="text-slate-100 min-h-screen grid-bg flex items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- Ambient Universe Glows --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    </div>

    <div class="relative w-full max-w-md z-10" x-data="{ showPass: false, showPassConfirm: false }">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8 float">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-600 to-cyan-500 mb-4 shadow-lg shadow-purple-500/30">
                <span class="text-3xl">✨</span>
            </div>
            <h1 class="text-2xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 uppercase">
                REVELIORA
            </h1>
            <p class="text-slate-500 text-sm mt-1">Daftar Akun Baru Velorans</p>
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
                            <span x-show="!showPass" class="text-sm">👁️</span>
                            <span x-show="showPass" class="text-sm">🙈</span>
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
                            <span x-show="!showPassConfirm" class="text-sm">👁️</span>
                            <span x-show="showPassConfirm" class="text-sm">🙈</span>
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
