<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Terlalu Besar – SPECTA REVELIORA</title>
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

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
            20%, 40%, 60%, 80% { transform: translateX(4px); }
        }
        .shake-once { animation: shake 0.6s ease-in-out; }

        @keyframes pulse-icon {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.05); opacity: 1; }
        }
        .pulse-icon { animation: pulse-icon 2s ease-in-out infinite; }
    </style>
</head>
<body class="text-slate-100 min-h-screen grid-bg flex items-center justify-center px-4 py-12 relative overflow-x-hidden">

    {{-- Ambient Universe Glows --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute top-1/3 right-1/4 w-[250px] h-[250px] bg-amber-600/5 rounded-full blur-[100px] pointer-events-none"></div>
    </div>

    <div class="relative w-full max-w-md z-10">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8 float flex justify-center">
            <img src="{{ asset('images/logo_specta.png') }}" alt="Specta Logo" class="h-24 object-contain drop-shadow-[0_0_15px_rgba(186,131,255,0.4)]">
        </div>

        {{-- Card --}}
        <div class="bg-slate-900/80 backdrop-blur-2xl border border-slate-800/60 rounded-3xl p-8 shadow-2xl glow-purple text-center shake-once">

            {{-- File Icon --}}
            <div class="relative flex justify-center mb-6">
                <div class="relative w-16 h-16 bg-gradient-to-br from-amber-500/20 to-red-600/20 rounded-2xl flex items-center justify-center border border-amber-500/30 pulse-icon">
                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12H9.75m.75-9V3.375c0-.621-.504-1.125-1.125-1.125H6.375A2.625 2.625 0 003.75 4.875v14.25A2.625 2.625 0 006.375 21.75h11.25A2.625 2.625 0 0020.25 19.125V8.25a2.625 2.625 0 00-2.625-2.625h-.375"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m0 3h.008v.008H12v-.008z"/>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-slate-100 mb-2">File Terlalu Besar</h2>
            <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                File yang Anda kirim melebihi batas ukuran maksimum.<br>
                Mohon kompres atau kirim ulang dengan file yang lebih kecil.
            </p>

            {{-- Tips Box --}}
            <div class="bg-slate-800/60 border border-slate-700/40 rounded-2xl p-5 mb-6 text-left space-y-3">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-2">Tips mengurangi ukuran file</p>

                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Screenshot bukti pembayaran, jangan foto layar HP
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Gunakan format <span class="text-slate-200 font-semibold">JPG</span> atau <span class="text-slate-200 font-semibold">JPEG</span> — lebih kecil dari PNG
                    </p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Maksimal ukuran file: <span class="text-slate-200 font-semibold">10 MB</span>
                    </p>
                </div>
            </div>

            {{-- Action Button --}}
            <a
                href="{{ url()->previous() }}"
                id="btn-back-payment"
                class="inline-flex items-center justify-center gap-2 w-full bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-purple-900/30 hover:shadow-purple-500/20 hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-wide"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Kirim Ulang Bukti Pembayaran
            </a>

            <a href="{{ url('/') }}" class="inline-block mt-3 text-sm text-slate-500 hover:text-purple-400 transition-colors">
                Kembali ke beranda
            </a>
        </div>

        <p class="text-center text-slate-600 text-xs mt-6">
            &copy; {{ date('Y') }} SPECTA XXI · SMAN 1 Cianjur
        </p>
    </div>

</body>
</html>
