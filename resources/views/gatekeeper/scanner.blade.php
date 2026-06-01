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
            <div id="reader" class="rounded-2xl overflow-hidden bg-black aspect-square w-full border-2 border-cyan-500 shadow-[0_0_25px_rgba(6,182,212,0.6)]"></div>
            
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
            <button @click="resetScanner()" class="absolute top-4 right-4 text-slate-500 hover:text-slate-300"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/></svg></button>
            
            <div class="text-center mb-4">
                <div class="text-5xl mb-3" x-text="resultIcon()"></div>
                <h3 class="font-bold text-xl" 
                    :class="{
                        'text-green-400': result?.type === 'valid',
                        'text-red-400': result?.type === 'invalid',
                        'text-cyan-400': result?.type === 'duplicate'
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
                <div class="bg-cyan-900/20 rounded-xl p-4 border border-cyan-700/30 text-center">
                    <p class="text-xs text-cyan-400/80 uppercase tracking-widest mb-1">Waktu Scan Pertama</p>
                    <p class="font-mono text-cyan-400 font-bold" x-text="result?.data?.scanned_at"></p>
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
                        setTimeout(() => { if(this.result?.type === 'valid') this.resetScanner() }, 3000);
                    } else if (response.status === 409) {
                        this.result = { type: 'duplicate', message: data.message, data: data };
                        this.playBeep('error');
                        setTimeout(() => { if(this.result?.type === 'duplicate') this.resetScanner() }, 3000);
                    } else {
                        this.result = { type: 'invalid', message: data.message || 'Kode tidak valid', data: null };
                        this.playBeep('error');
                        setTimeout(() => { if(this.result?.type === 'invalid') this.resetScanner() }, 3000);
                    }
                } catch (error) {
                    this.result = { type: 'invalid', message: 'Koneksi terputus. Coba lagi.', data: null };
                    this.playBeep('error');
                    setTimeout(() => { if(this.result?.type === 'invalid') this.resetScanner() }, 3000);
                } finally {
                    this.isProcessing = false;
                }
            },

            resetScanner() {
                this.result = null;
                if(this.scanner && this.scanner.isScanning) this.scanner.resume();
            },

            resultIcon() {
                if(this.result?.type === 'valid') return '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2m-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8z"/></svg>';
                if(this.result?.type === 'invalid') return '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.53 0 10 4.47 10 10s-4.47 10-10 10S2 17.53 2 12S6.47 2 12 2m3.59 5L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"/></svg>';
                if(this.result?.type === 'duplicate') return '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M13 14h-2V9h2m0 9h-2v-2h2M1 21h22L12 2z"/></svg>';
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
                        // Pleasant double chime
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, ctx.currentTime); // A5
                        osc.frequency.setValueAtTime(1108.73, ctx.currentTime + 0.1); // C#6
                        
                        gain.gain.setValueAtTime(0, ctx.currentTime);
                        gain.gain.linearRampToValueAtTime(1, ctx.currentTime + 0.02);
                        gain.gain.linearRampToValueAtTime(0, ctx.currentTime + 0.3);
                        
                        osc.start(ctx.currentTime);
                        osc.stop(ctx.currentTime + 0.3);
                    } else {
                        // Harsh error buzz
                        osc.type = 'sawtooth';
                        osc.frequency.setValueAtTime(150, ctx.currentTime);
                        osc.frequency.exponentialRampToValueAtTime(100, ctx.currentTime + 0.3);
                        
                        gain.gain.setValueAtTime(0, ctx.currentTime);
                        gain.gain.linearRampToValueAtTime(1, ctx.currentTime + 0.05);
                        gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                        
                        osc.start(ctx.currentTime);
                        osc.stop(ctx.currentTime + 0.3);
                    }
                } catch(e) { console.log('Audio disabled') }
            }
        };
    }
</script>
@endpush
@endsection
