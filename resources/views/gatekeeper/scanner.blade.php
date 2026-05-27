@extends('layouts.app')

@section('title', 'Gate Scanner – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 flex flex-col items-center py-8 px-4" x-data="scannerApp()" x-init="initScanner()">
    
    <div class="w-full max-w-md">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 tracking-widest uppercase">
                GATE SCANNER
            </h1>
            <p class="text-slate-400 text-sm mt-1">SPECTA XXI: REVELIORA</p>
        </div>

        {{-- Main Scanner Area --}}
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800/60 rounded-3xl p-4 shadow-2xl mb-6 relative overflow-hidden">
            {{-- Scanner Viewport --}}
            <div id="reader" class="rounded-2xl overflow-hidden bg-black aspect-square w-full"></div>
            
            {{-- Processing Overlay --}}
            <div x-show="isProcessing" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm z-10 flex flex-col items-center justify-center" x-cloak>
                <div class="w-12 h-12 border-4 border-purple-500/30 border-t-purple-500 rounded-full animate-spin mb-4"></div>
                <p class="text-purple-300 font-semibold animate-pulse">Memvalidasi tiket...</p>
            </div>
        </div>

        {{-- Manual Input Fallback --}}
        <div class="bg-slate-900/60 border border-slate-800/60 rounded-2xl p-4 mb-6" x-show="!isProcessing">
            <form @submit.prevent="processManualCode()" class="flex gap-2">
                <input type="text" x-model="manualCode" placeholder="Atau ketik kode TKT-XXXX..." class="flex-1 bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 text-sm text-slate-200 focus:outline-none focus:border-purple-500 font-mono uppercase">
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-4 py-2.5 rounded-xl font-medium transition-colors text-sm border border-slate-700">Cek</button>
            </form>
        </div>

        {{-- Results Modal / Alert --}}
        <div x-show="result" x-transition class="bg-slate-900/90 backdrop-blur-xl border rounded-2xl p-6 relative overflow-hidden" 
             :class="{
                'border-green-500/50 shadow-[0_0_30px_rgba(34,197,94,0.15)]': result?.type === 'valid',
                'border-red-500/50 shadow-[0_0_30px_rgba(239,68,68,0.15)]': result?.type === 'invalid',
                'border-yellow-500/50 shadow-[0_0_30px_rgba(234,179,8,0.15)]': result?.type === 'duplicate'
             }" x-cloak>
            
            {{-- Close btn --}}
            <button @click="resetScanner()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-300">✕</button>
            
            <div class="text-center mb-4">
                <div class="text-5xl mb-3" x-text="resultIcon()"></div>
                <h3 class="font-bold text-xl" 
                    :class="{
                        'text-green-400': result?.type === 'valid',
                        'text-red-400': result?.type === 'invalid',
                        'text-yellow-400': result?.type === 'duplicate'
                    }" x-text="resultTitle()"></h3>
                <p class="text-slate-400 text-sm mt-1" x-text="result?.message"></p>
            </div>

            {{-- Valid details --}}
            <template x-if="result?.type === 'valid'">
                <div class="bg-slate-800/50 rounded-xl p-4 border border-slate-700/50 space-y-3">
                    <div class="flex justify-between border-b border-slate-700/50 pb-2">
                        <span class="text-slate-500 text-xs uppercase">Nama</span>
                        <span class="text-slate-200 font-semibold text-right" x-text="result?.data?.buyer_name"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-700/50 pb-2">
                        <span class="text-slate-500 text-xs uppercase">Kelas</span>
                        <span class="text-slate-200 font-semibold text-right" x-text="result?.data?.buyer_class"></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-700/50 pb-2">
                        <span class="text-slate-500 text-xs uppercase">Tiket</span>
                        <span class="text-cyan-400 font-bold text-right" x-text="result?.data?.ticket_name"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500 text-xs uppercase">Invoice</span>
                        <span class="text-purple-300 font-mono text-xs text-right" x-text="result?.data?.invoice"></span>
                    </div>
                </div>
            </template>

            {{-- Duplicate details --}}
            <template x-if="result?.type === 'duplicate'">
                <div class="bg-yellow-900/20 rounded-xl p-4 border border-yellow-700/30 text-center">
                    <p class="text-xs text-yellow-500/80 uppercase tracking-widest mb-1">Waktu Scan Pertama</p>
                    <p class="font-mono text-yellow-400 font-bold" x-text="result?.data?.scanned_at"></p>
                </div>
            </template>

            <button @click="resetScanner()" class="w-full mt-6 bg-slate-800 hover:bg-slate-700 text-white font-semibold py-3 rounded-xl transition-colors border border-slate-700">
                Scan Lagi
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function scannerApp() {
        return {
            scanner: null,
            isProcessing: false,
            manualCode: '',
            result: null, // { type: 'valid'|'invalid'|'duplicate', message: string, data: object }

            initScanner() {
                this.scanner = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
                
                this.scanner.start({ facingMode: "environment" }, config, this.onScanSuccess.bind(this))
                    .catch(err => {
                        console.error("Camera start error:", err);
                        alert("Gagal mengakses kamera. Pastikan izin diberikan.");
                    });
            },

            async onScanSuccess(decodedText) {
                if (this.isProcessing || this.result) return;
                
                // Pause scanner without stopping camera
                this.scanner.pause();
                this.isProcessing = true;

                await this.verifyCode(decodedText);
            },

            async processManualCode() {
                if (!this.manualCode.trim() || this.isProcessing) return;
                this.isProcessing = true;
                if(this.scanner && this.scanner.isScanning) this.scanner.pause();
                
                await this.verifyCode(this.manualCode.trim().toUpperCase());
                this.manualCode = '';
            },

            async verifyCode(code) {
                try {
                    const response = await fetch('{{ route("gatekeeper.scan") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ code })
                    });

                    const data = await response.json();
                    
                    if (response.ok) {
                        this.result = { type: 'valid', message: data.message, data: data };
                        this.playBeep('success');
                    } else if (response.status === 409) {
                        this.result = { type: 'duplicate', message: data.message, data: data };
                        this.playBeep('error');
                    } else {
                        this.result = { type: 'invalid', message: data.message || 'Kode tidak valid', data: null };
                        this.playBeep('error');
                    }
                } catch (error) {
                    this.result = { type: 'invalid', message: 'Koneksi terputus. Coba lagi.', data: null };
                    this.playBeep('error');
                } finally {
                    this.isProcessing = false;
                }
            },

            resetScanner() {
                this.result = null;
                if(this.scanner && this.scanner.isScanning) this.scanner.resume();
            },

            resultIcon() {
                if(this.result?.type === 'valid') return '✅';
                if(this.result?.type === 'invalid') return '❌';
                if(this.result?.type === 'duplicate') return '⚠️';
                return '';
            },

            resultTitle() {
                if(this.result?.type === 'valid') return 'BERHASIL';
                if(this.result?.type === 'invalid') return 'DITOLAK';
                if(this.result?.type === 'duplicate') return 'DUPLIKAT';
                return '';
            },

            playBeep(type) {
                // Simple web audio beep fallback
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    
                    if (type === 'success') {
                        osc.frequency.value = 880; // A5
                        osc.type = 'sine';
                    } else {
                        osc.frequency.value = 200; // Low frequency for error
                        osc.type = 'sawtooth';
                    }
                    
                    osc.start();
                    gain.gain.exponentialRampToValueAtTime(0.00001, ctx.currentTime + 0.5);
                    osc.stop(ctx.currentTime + 0.5);
                } catch(e) { console.log('Audio disabled') }
            }
        };
    }
</script>
@endpush
@endsection
