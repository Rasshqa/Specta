@extends('layouts.app')

@section('title', 'SPECTA XXI – Gate Scanner')

@push('head')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;600;700&display=swap');

    :root {
        --neon-cyan:   #00e5ff;
        --neon-purple: #a855f7;
        --neon-green:  #00ff88;
        --neon-red:    #ff3355;
        --bg-dark:     #020617;
    }

    .scanner-glow {
        box-shadow:
            0 0 25px rgba(0, 229, 255, 0.7),
            0 0 60px rgba(0, 229, 255, 0.35),
            inset 0 0 25px rgba(0, 229, 255, 0.08);
    }

    .neon-text {
        font-family: 'Orbitron', monospace;
        text-shadow: 0 0 12px currentColor, 0 0 30px currentColor;
    }

    /* Animated scan line across the camera */
    @keyframes scanLine {
        0%   { top: 10%; opacity: 0.8; }
        50%  { opacity: 0.4; }
        100% { top: 90%; opacity: 0.8; }
    }

    .scan-line {
        position: absolute;
        left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--neon-cyan), transparent);
        box-shadow: 0 0 8px var(--neon-cyan);
        animation: scanLine 2.4s ease-in-out infinite alternate;
        pointer-events: none;
        z-index: 5;
    }

    /* Corner brackets overlay */
    .corner-bracket {
        position: absolute;
        width: 28px; height: 28px;
        border-color: var(--neon-cyan);
        border-style: solid;
        z-index: 6;
    }
    .corner-tl { top: 12px; left: 12px; border-width: 3px 0 0 3px; }
    .corner-tr { top: 12px; right: 12px; border-width: 3px 3px 0 0; }
    .corner-bl { bottom: 12px; left: 12px; border-width: 0 0 3px 3px; }
    .corner-br { bottom: 12px; right: 12px; border-width: 0 3px 3px 0; }

    /* Pulse ring for valid scan */
    @keyframes pulseValid {
        0%   { transform: scale(1);   opacity: 1; }
        100% { transform: scale(1.8); opacity: 0; }
    }

    @keyframes pulseInvalid {
        0%, 100% { transform: translateX(0); }
        20%      { transform: translateX(-6px); }
        40%      { transform: translateX(6px); }
        60%      { transform: translateX(-4px); }
        80%      { transform: translateX(4px); }
    }

    .shake { animation: pulseInvalid 0.4s ease; }

    /* html5-qrcode overrides for dark theme */
    #reader video            { border-radius: 12px !important; }
    #reader__scan_region     { background: transparent !important; }
    #reader__dashboard       { display: none !important; }
    #reader__header_message  { display: none !important; }
    #reader__camera_permission_button { display: none !important; }
    #reader img              { display: none !important; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#020617] text-slate-100 flex flex-col items-center px-4 py-8 relative overflow-hidden"
     x-data="gatekeeperApp()" x-init="init()">

    {{-- Ambient glows --}}
    <div class="pointer-events-none fixed inset-0 z-0">
        <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-cyan-900/15 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] bg-purple-900/15 rounded-full blur-[120px]"></div>
    </div>

    {{-- ── Back to Admin ─────────────────────────────────────────────────── --}}
    <div class="w-full max-w-md mb-4 relative z-10">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-cyan-400 text-sm transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

    <div class="w-full max-w-md relative z-10">

        {{-- ── Header ─────────────────────────────────────────────────────── --}}
        <div class="text-center mb-6">
            <p class="text-[10px] tracking-[0.4em] text-cyan-500 uppercase mb-1 font-semibold">SPECTA XXI · REVELIORA</p>
            <h1 class="neon-text text-3xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 uppercase">
                GATE SCANNER
            </h1>
            <div class="flex items-center justify-center gap-2 mt-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <p class="text-xs text-slate-400">System Active</p>
            </div>
        </div>

        {{-- ── Camera Scanner Card ─────────────────────────────────────────── --}}
        <div class="relative bg-slate-900/70 backdrop-blur-xl border border-cyan-500/30 rounded-2xl p-3 mb-4 scanner-glow">

            {{-- Scanner viewport --}}
            <div class="relative rounded-xl overflow-hidden bg-black aspect-square">
                <div id="reader" class="w-full h-full"></div>

                {{-- Overlay elements --}}
                <div class="scan-line" x-show="!result && !isProcessing"></div>
                <div class="corner-bracket corner-tl"></div>
                <div class="corner-bracket corner-tr"></div>
                <div class="corner-bracket corner-bl"></div>
                <div class="corner-bracket corner-br"></div>

                {{-- Processing overlay --}}
                <div x-show="isProcessing" x-transition
                     class="absolute inset-0 bg-slate-950/85 backdrop-blur-sm z-20 flex flex-col items-center justify-center gap-3">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-full border-2 border-cyan-500/20 border-t-cyan-400 animate-spin"></div>
                        <div class="absolute inset-2 w-10 h-10 rounded-full border-2 border-purple-500/20 border-b-purple-400 animate-spin" style="animation-direction: reverse; animation-duration: 0.7s;"></div>
                    </div>
                    <p class="text-cyan-300 text-sm font-semibold tracking-wider animate-pulse">VALIDATING...</p>
                </div>
            </div>

            {{-- Scan counter badge --}}
            <div class="absolute top-5 right-5 bg-slate-950/80 border border-cyan-500/30 rounded-xl px-3 py-1.5 text-center z-10">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest">Scans</p>
                <p class="text-xl font-black font-mono text-cyan-400 neon-text" x-text="scanCount">0</p>
            </div>
        </div>

        {{-- ── Result Panel ────────────────────────────────────────────────── --}}
        <div x-show="result" x-transition.opacity.duration.300ms
             class="rounded-2xl p-5 mb-4 relative overflow-hidden border transition-all duration-300"
             :class="{
                 'border-green-500/50 bg-green-950/30 shadow-[0_0_30px_rgba(0,255,136,0.12)]': result?.status === 'valid',
                 'border-red-500/50   bg-red-950/30   shadow-[0_0_30px_rgba(255,51,85,0.12)]' : result?.status === 'invalid',
                 'border-yellow-500/50 bg-yellow-950/30 shadow-[0_0_30px_rgba(234,179,8,0.12)]': result?.status === 'duplicate',
             }"
             :id="'result-panel-' + result?.status"
             x-cloak>

            {{-- Animated accent bar --}}
            <div class="absolute top-0 left-0 right-0 h-0.5"
                 :class="{
                     'bg-gradient-to-r from-transparent via-green-400 to-transparent': result?.status === 'valid',
                     'bg-gradient-to-r from-transparent via-red-400 to-transparent':   result?.status === 'invalid',
                     'bg-gradient-to-r from-transparent via-yellow-400 to-transparent': result?.status === 'duplicate',
                 }"></div>

            <div class="flex items-start gap-4">
                {{-- Icon --}}
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-xl animate-bounce"
                     :class="{
                         'bg-green-500/15 border border-green-500/30': result?.status === 'valid',
                         'bg-red-500/15 border border-red-500/30':     result?.status === 'invalid',
                         'bg-yellow-500/15 border border-yellow-500/30': result?.status === 'duplicate',
                     }"
                     x-text="result?.status === 'valid' ? '' : result?.status === 'duplicate' ? '' : ''">
                </div>

                <div class="flex-1 min-w-0">
                    {{-- Status title --}}
                    <p class="text-xs tracking-[0.3em] uppercase font-bold mb-1"
                       :class="{
                           'text-green-400':  result?.status === 'valid',
                           'text-red-400':    result?.status === 'invalid',
                           'text-yellow-400': result?.status === 'duplicate',
                       }"
                       x-text="result?.status === 'valid' ? 'TIKET VALID' : result?.status === 'duplicate' ? 'SUDAH DIGUNAKAN' : 'TIKET DITOLAK'">
                    </p>

                    {{-- Buyer name (valid) --}}
                    <template x-if="result?.buyer_name">
                        <p class="text-lg font-black text-white truncate" x-text="result.buyer_name"></p>
                    </template>

                    <p class="text-sm text-slate-400 mt-0.5" x-text="result?.message"></p>

                    {{-- Extra info for valid --}}
                    <template x-if="result?.status === 'valid'">
                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1">
                            <span class="text-xs text-cyan-400" x-text="result?.ticket_name"></span>
                            <span class="text-xs text-purple-400 font-mono" x-text="result?.invoice"></span>
                        </div>
                    </template>

                    {{-- Scanned at for duplicate --}}
                    <template x-if="result?.status === 'duplicate' && result?.scanned_at">
                        <p class="text-xs text-yellow-400/80 mt-1 font-mono" x-text="'First scan: ' + result.scanned_at"></p>
                    </template>
                </div>
            </div>

            {{-- Auto-close progress bar --}}
            <div class="mt-4 h-0.5 w-full rounded-full bg-white/5 overflow-hidden">
                <div class="h-full rounded-full transition-all ease-linear"
                     :class="{
                         'bg-green-400':  result?.status === 'valid',
                         'bg-red-400':    result?.status === 'invalid',
                         'bg-yellow-400': result?.status === 'duplicate',
                     }"
                     :style="'width:' + autoCloseProgress + '%; transition-duration:' + autoCloseMs + 'ms'">
                </div>
            </div>
        </div>

        {{-- ── Manual Code Input ───────────────────────────────────────────── --}}
        <div class="bg-slate-900/60 border border-slate-800/60 rounded-2xl p-4 mb-4" x-show="!isProcessing">
            <p class="text-[10px] text-slate-500 uppercase tracking-widest mb-2 font-semibold">Input Manual Kode</p>
            <form @submit.prevent="submitManual()" class="flex gap-2">
                <input type="text"
                       id="manual-code-input"
                       x-model="manualCode"
                       placeholder="TKT-XXXXX-XXXXX-1"
                       maxlength="30"
                       autocomplete="off"
                       class="flex-1 bg-slate-800/60 border border-slate-700/50 focus:border-cyan-500/50 rounded-xl px-4 py-2.5 text-sm text-slate-100 font-mono uppercase tracking-widest placeholder-slate-600 focus:outline-none transition-colors">
                <button type="submit"
                        id="manual-submit-btn"
                        class="bg-cyan-600/20 hover:bg-cyan-600/40 border border-cyan-500/40 text-cyan-300 px-5 py-2.5 rounded-xl font-bold text-sm transition-all hover:shadow-[0_0_15px_rgba(0,229,255,0.2)]">
                    CEK
                </button>
            </form>
        </div>

        {{-- ── Stats Bar ────────────────────────────────────────────────────── --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="bg-slate-900/60 border border-slate-800/60 rounded-xl p-3 text-center">
                <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Valid</p>
                <p class="text-xl font-black text-green-400 font-mono" x-text="stats.valid">0</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800/60 rounded-xl p-3 text-center">
                <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Duplikat</p>
                <p class="text-xl font-black text-yellow-400 font-mono" x-text="stats.duplicate">0</p>
            </div>
            <div class="bg-slate-900/60 border border-slate-800/60 rounded-xl p-3 text-center">
                <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Ditolak</p>
                <p class="text-xl font-black text-red-400 font-mono" x-text="stats.invalid">0</p>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Html5-QRCode library --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    function gatekeeperApp() {
        return {
            scanner:           null,
            isProcessing:      false,
            manualCode:        '',
            result:            null,
            scanCount:         0,
            autoCloseProgress: 100,
            autoCloseMs:       3000,

            stats: { valid: 0, duplicate: 0, invalid: 0 },

            init() {
                this.startScanner();
            },

            startScanner() {
                this.scanner = new Html5Qrcode('reader');

                const config = {
                    fps:         15,
                    qrbox:       { width: 240, height: 240 },
                    aspectRatio: 1.0,
                    formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
                };

                this.scanner
                    .start({ facingMode: 'environment' }, config, this.onScanSuccess.bind(this))
                    .catch(() => {
                        this.scanner
                            .start({ facingMode: 'user' }, config, this.onScanSuccess.bind(this))
                            .catch(err => {
                                console.error('Camera error:', err);
                                alert('Gagal mengakses kamera. Pastikan izin kamera diberikan.');
                            });
                    });
            },

            async onScanSuccess(decodedText) {
                if (this.isProcessing || this.result) return;
                this.scanner.pause();
                this.isProcessing = true;
                await this.verifyCode(decodedText);
            },

            async submitManual() {
                const code = this.manualCode.trim().toUpperCase();
                if (!code || this.isProcessing) return;
                this.isProcessing = true;
                if (this.scanner?.isScanning) this.scanner.pause();
                await this.verifyCode(code);
                this.manualCode = '';
            },

            async verifyCode(code) {
                try {
                    const res = await fetch('{{ route("gatekeeper.scan") }}', {
                        method:  'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept':       'application/json',
                        },
                        body: JSON.stringify({ code }),
                    });

                    const data = await res.json();
                    this.scanCount++;

                    if (res.ok) {
                        // Valid ticket
                        this.result = { ...data, status: 'valid' };
                        this.stats.valid++;
                        this.playTone('success');
                    } else if (res.status === 409) {
                        // Already scanned
                        this.result = { ...data, status: 'duplicate' };
                        this.stats.duplicate++;
                        this.playTone('warning');
                    } else {
                        // Invalid / not found / not paid
                        this.result = { ...data, status: 'invalid' };
                        this.stats.invalid++;
                        this.playTone('error');
                    }

                    this.startAutoClose();

                } catch (err) {
                    this.result = { status: 'invalid', message: 'Koneksi terputus. Coba lagi.' };
                    this.stats.invalid++;
                    this.playTone('error');
                    this.startAutoClose();
                } finally {
                    this.isProcessing = false;
                }
            },

            startAutoClose() {
                // Animate the progress bar from 100 → 0 over 3 seconds
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.autoCloseProgress = 0;
                    }, 50); // tiny delay so the DOM sees 100 first

                    setTimeout(() => {
                        this.resetResult();
                    }, 3050);
                });
            },

            resetResult() {
                this.result            = null;
                this.autoCloseProgress = 100;

                if (this.scanner?.isScanning) {
                    try { this.scanner.resume(); } catch(e) {}
                }
            },

            playTone(type) {
                try {
                    const ctx  = new (window.AudioContext || window.webkitAudioContext)();
                    const gain = ctx.createGain();
                    gain.connect(ctx.destination);

                    if (type === 'success') {
                        // Rising double chime
                        [880, 1108].forEach((freq, i) => {
                            const osc = ctx.createOscillator();
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(freq, ctx.currentTime + i * 0.13);
                            gain.gain.setValueAtTime(0, ctx.currentTime + i * 0.13);
                            gain.gain.linearRampToValueAtTime(0.7, ctx.currentTime + i * 0.13 + 0.03);
                            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + i * 0.13 + 0.25);
                            osc.connect(gain);
                            osc.start(ctx.currentTime + i * 0.13);
                            osc.stop(ctx.currentTime + i * 0.13 + 0.25);
                        });
                    } else if (type === 'warning') {
                        // Mid tone blip
                        const osc = ctx.createOscillator();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(440, ctx.currentTime);
                        gain.gain.setValueAtTime(0.6, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
                        osc.connect(gain);
                        osc.start(ctx.currentTime);
                        osc.stop(ctx.currentTime + 0.35);
                    } else {
                        // Descending error buzz
                        const osc = ctx.createOscillator();
                        osc.type = 'sawtooth';
                        osc.frequency.setValueAtTime(220, ctx.currentTime);
                        osc.frequency.exponentialRampToValueAtTime(80, ctx.currentTime + 0.4);
                        gain.gain.setValueAtTime(0.7, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                        osc.connect(gain);
                        osc.start(ctx.currentTime);
                        osc.stop(ctx.currentTime + 0.4);
                    }
                } catch (e) { /* Audio not available */ }
            },
        };
    }
</script>
@endpush
