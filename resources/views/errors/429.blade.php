<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terlalu Banyak Percobaan – SPECTA REVELIORA</title>
    @vite(['resources/css/app.css'])
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

        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.6; }
            50% { transform: scale(1.1); opacity: 0.2; }
            100% { transform: scale(0.8); opacity: 0.6; }
        }
        .pulse-ring { animation: pulse-ring 2s ease-in-out infinite; }

        @keyframes countdown-glow {
            0%, 100% { text-shadow: 0 0 20px rgba(186,131,255,0.5); }
            50% { text-shadow: 0 0 40px rgba(186,131,255,0.8), 0 0 60px rgba(6,182,212,0.3); }
        }
        .countdown-glow { animation: countdown-glow 2s ease-in-out infinite; }

        .progress-bar {
            background: linear-gradient(90deg, #9333ea, #06b6d4);
            transition: width 1s linear;
        }
    </style>
</head>
<body class="text-slate-100 min-h-screen grid-bg flex items-center justify-center px-4 py-12 relative overflow-x-hidden">

    {{-- Ambient Universe Glows --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-red-600/5 rounded-full blur-[100px] pointer-events-none"></div>
    </div>

    <div class="relative w-full max-w-md z-10">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8 float flex justify-center">
            <img src="{{ asset('images/logo_specta.png') }}" alt="Specta Logo" class="h-24 object-contain drop-shadow-[0_0_15px_rgba(186,131,255,0.4)]">
        </div>

        {{-- Card --}}
        <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-800/60 rounded-3xl p-8 shadow-2xl glow-purple text-center">

            {{-- Shield Icon with Pulse --}}
            <div class="relative flex justify-center mb-6">
                <div class="absolute w-20 h-20 bg-amber-500/10 rounded-full pulse-ring"></div>
                <div class="relative w-16 h-16 bg-gradient-to-br from-amber-500/20 to-orange-600/20 rounded-2xl flex items-center justify-center border border-amber-500/30">
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-slate-100 mb-2">Terlalu Banyak Percobaan</h2>
            <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                Anda telah melakukan terlalu banyak percobaan login.<br>
                Demi keamanan, mohon tunggu sebentar sebelum mencoba lagi.
            </p>

            {{-- Countdown Timer --}}
            <div class="bg-slate-800/60 border border-slate-700/40 rounded-2xl p-6 mb-6" x-data="countdownTimer()" x-init="start()">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-3">Coba lagi dalam</p>
                <div class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 countdown-glow mb-3" x-text="display">
                    1:00
                </div>
                <div class="w-full bg-slate-700/50 rounded-full h-1.5 overflow-hidden">
                    <div class="progress-bar h-full rounded-full" :style="'width: ' + percentage + '%'"></div>
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-slate-800/40 border border-slate-700/30 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3 text-left">
                    <svg class="w-5 h-5 text-cyan-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>
                    </svg>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Batas percobaan adalah <span class="text-slate-200 font-semibold">5 kali per menit</span>.
                        Pastikan email dan password Anda benar sebelum mencoba kembali.
                    </p>
                </div>
            </div>

            {{-- Action Button --}}
            <a
                href="{{ route('login') }}"
                id="btn-back-login"
                class="inline-flex items-center justify-center gap-2 w-full bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-purple-900/30 hover:shadow-purple-500/20 hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-wide"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Halaman Login
            </a>

            <a href="{{ url('/') }}" class="inline-block mt-3 text-sm text-slate-500 hover:text-purple-400 transition-colors">
                Atau kembali ke beranda
            </a>
        </div>

        <p class="text-center text-slate-600 text-xs mt-6">
            &copy; {{ date('Y') }} SPECTA XXI · SMAN 1 Cianjur
        </p>
    </div>

    <script>
        function countdownTimer() {
            return {
                seconds: 60,
                total: 60,
                display: '1:00',
                percentage: 100,
                interval: null,

                start() {
                    this.interval = setInterval(() => {
                        this.seconds--;
                        if (this.seconds <= 0) {
                            clearInterval(this.interval);
                            this.seconds = 0;
                            this.display = '0:00';
                            this.percentage = 0;
                            // Auto redirect ke login setelah countdown selesai
                            window.location.href = '{{ route("login") }}';
                            return;
                        }
                        const mins = Math.floor(this.seconds / 60);
                        const secs = this.seconds % 60;
                        this.display = mins + ':' + (secs < 10 ? '0' : '') + secs;
                        this.percentage = (this.seconds / this.total) * 100;
                    }, 1000);
                }
            };
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
