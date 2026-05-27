@extends('layouts.app')

@section('title', 'Instruksi Pembayaran – ' . $transaction->invoice_number)

@section('content')
<div class="min-h-screen bg-slate-950 flex items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- Ambient glow blobs --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-700/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-2xl" x-data="paymentTimer('{{ $transaction->invoice_number }}', '{{ $transaction->status }}')" x-init="init()">

        {{-- Header badge --}}
        <div class="flex items-center justify-center mb-6" data-aos="fade-down">
            <div class="inline-flex items-center gap-2 bg-purple-900/40 border border-purple-500/40 text-purple-300 text-sm font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm">
                <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm0 14a6 6 0 110-12 6 6 0 010 12zm.75-8.25a.75.75 0 00-1.5 0V10c0 .199.079.39.22.53l2 2a.75.75 0 001.06-1.06l-1.78-1.78V7.75z"/></svg>
                Menunggu Konfirmasi Pembayaran
            </div>
        </div>

        {{-- Main card --}}
        <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-2xl shadow-purple-900/20 overflow-hidden" data-aos="fade-up" data-aos-delay="100">

            {{-- Card header stripe --}}
            <div class="h-1.5 w-full bg-gradient-to-r from-purple-600 via-blue-500 to-cyan-400"></div>

            <div class="p-8">

                {{-- Event branding --}}
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 uppercase">
                        SPECTA XXI
                    </h1>
                    <p class="text-slate-400 text-sm mt-1 tracking-wider uppercase">REVELIORA · Celestial Treasure</p>
                </div>

                {{-- Invoice info grid --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-slate-800/60 rounded-2xl p-4 border border-slate-700/40">
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">No. Invoice</p>
                        <p class="text-sm font-mono font-bold text-purple-300">{{ $transaction->invoice_number }}</p>
                    </div>
                    <div class="bg-slate-800/60 rounded-2xl p-4 border border-slate-700/40">
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Nama Pembeli</p>
                        <p class="text-sm font-semibold text-slate-200 truncate">{{ $transaction->buyer_name }}</p>
                    </div>
                    <div class="bg-slate-800/60 rounded-2xl p-4 border border-slate-700/40">
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Jenis Tiket</p>
                        <p class="text-sm font-semibold text-cyan-300">{{ $transaction->ticket->ticket_name }}</p>
                    </div>
                    <div class="bg-slate-800/60 rounded-2xl p-4 border border-slate-700/40">
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Jumlah Tiket</p>
                        <p class="text-sm font-semibold text-slate-200">{{ $transaction->quantity }}x</p>
                    </div>
                </div>

                {{-- Payment total box --}}
                <div class="bg-gradient-to-br from-purple-900/50 to-blue-900/50 border border-purple-500/30 rounded-2xl p-6 mb-8">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Harga Dasar</p>
                            <p class="text-lg font-semibold text-slate-200">Rp {{ number_format($transaction->base_price, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 uppercase tracking-widest mb-1">Kode Unik Moota</p>
                            <p class="text-lg font-semibold text-yellow-400">+ Rp {{ $transaction->unique_code }}</p>
                        </div>
                    </div>
                    <div class="border-t border-purple-500/20 pt-4">
                        <div class="flex items-center justify-between">
                            <p class="text-slate-300 font-semibold uppercase tracking-wide text-sm">Total Transfer</p>
                            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-cyan-300 font-mono">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <p class="text-xs text-yellow-400/80 mt-2">
                            ⚠️ Transfer <strong>tepat</strong> dengan nominal di atas agar terdeteksi otomatis oleh sistem.
                        </p>
                    </div>
                </div>

                {{-- Bank account info --}}
                <div class="mb-8">
                    <p class="text-xs text-slate-500 uppercase tracking-widest mb-3">Transfer Ke Rekening</p>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between bg-slate-800/60 border border-slate-700/40 rounded-2xl px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center">
                                    <span class="text-blue-400 font-black text-xs">BCA</span>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Bank Central Asia</p>
                                    <p class="font-mono font-bold text-slate-100 text-lg tracking-widest">1234 5678 90</p>
                                    <p class="text-xs text-slate-400">a.n. SMAN 1 Cianjur SPECTA</p>
                                </div>
                            </div>
                            <button
                                id="btn-copy-rekening"
                                onclick="copyToClipboard('1234567890', this)"
                                class="text-xs text-purple-400 hover:text-purple-300 font-semibold border border-purple-500/30 hover:border-purple-400/60 rounded-xl px-3 py-2 transition-all duration-200"
                            >Salin</button>
                        </div>
                    </div>
                </div>

                {{-- Countdown timer --}}
                <div class="text-center mb-8" x-show="status === 'pending'">
                    <p class="text-xs text-slate-500 uppercase tracking-widest mb-2">Selesaikan dalam</p>
                    <div class="flex items-center justify-center gap-3">
                        <div class="bg-slate-800 border border-purple-500/30 rounded-xl px-4 py-3 min-w-[60px]">
                            <p class="text-2xl font-black font-mono text-purple-300" x-text="timeLeft.hours"></p>
                            <p class="text-xs text-slate-500 mt-1">JAM</p>
                        </div>
                        <span class="text-purple-400 text-2xl font-black animate-pulse">:</span>
                        <div class="bg-slate-800 border border-purple-500/30 rounded-xl px-4 py-3 min-w-[60px]">
                            <p class="text-2xl font-black font-mono text-purple-300" x-text="timeLeft.minutes"></p>
                            <p class="text-xs text-slate-500 mt-1">MENIT</p>
                        </div>
                        <span class="text-purple-400 text-2xl font-black animate-pulse">:</span>
                        <div class="bg-slate-800 border border-purple-500/30 rounded-xl px-4 py-3 min-w-[60px]">
                            <p class="text-2xl font-black font-mono text-purple-300" x-text="timeLeft.seconds"></p>
                            <p class="text-xs text-slate-500 mt-1">DETIK</p>
                        </div>
                    </div>
                </div>

                {{-- Status banners --}}
                <div x-show="status === 'success'" x-transition
                     class="bg-green-900/40 border border-green-500/40 rounded-2xl p-6 text-center mb-6 space-y-4">
                    <div class="text-5xl">✅</div>
                    <p class="text-green-400 font-bold text-xl">Pembayaran Dikonfirmasi!</p>
                    @if($transaction->status === 'success')
                    <p class="text-slate-400 text-sm">
                        E-Tiket kamu sudah siap untuk diunduh!
                    </p>

                    {{-- PRIMARY: Download Button --}}
                    <a href="{{ route('ticket.download', $transaction->download_token) }}"
                       id="btn-download-ticket"
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-black py-3.5 px-8 rounded-2xl transition-all duration-300 shadow-lg shadow-purple-900/50 hover:shadow-purple-500/30 hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-wide border border-purple-400/30">
                        <span class="text-xl">📥</span>
                        Download E-Tiket (PDF)
                    </a>

                    {{-- SECONDARY: Email note --}}
                    <p class="text-slate-600 text-xs pt-1">
                        Salinan juga dikirim ke <span class="text-slate-500">{{ $transaction->buyer_email }}</span>
                        &nbsp;·&nbsp; Cek folder <em>Spam</em> jika tidak ada di Inbox.
                    </p>

                    <a href="{{ route('tickets.index') }}"
                       class="inline-block text-xs text-green-400 hover:text-green-300 border border-green-500/30 hover:border-green-400/60 rounded-xl px-4 py-2 transition-all">
                        ← Kembali ke Halaman Tiket
                    </a>
                    @endif
                </div>

                <div x-show="status === 'expired'" x-transition class="bg-red-900/40 border border-red-500/40 rounded-2xl p-6 text-center mb-6 space-y-3">
                    <div class="text-5xl">❌</div>
                    <p class="text-red-400 font-bold text-xl">Transaksi Kedaluwarsa</p>
                    <p class="text-slate-400 text-sm">Waktu pembayaran habis dan kuota tiket telah dikembalikan.</p>
                    <a href="{{ route('tickets.index') }}"
                       class="inline-block mt-2 text-xs text-red-300 hover:text-red-200 border border-red-500/30 hover:border-red-400/60 rounded-xl px-4 py-2 transition-all">
                        Beli Tiket Lagi
                    </a>
                </div>

                {{-- Instructions --}}
                <div class="bg-slate-800/40 border border-slate-700/30 rounded-2xl p-5" x-show="status === 'pending'">
                    <p class="text-xs text-slate-400 uppercase tracking-widest mb-3 font-semibold">Langkah Pembayaran</p>
                    <ol class="space-y-2">
                        @foreach(['Transfer tepat nominal <strong class="text-yellow-400">Rp ' . number_format($transaction->total_price, 0, ',', '.') . '</strong> ke rekening BCA di atas.', 'Screenshot bukti transfer kamu.', 'Kirim bukti transfer via WhatsApp ke <strong class="text-green-400">0812-XXXX-XXXX</strong>.', 'Konfirmasi otomatis dalam <strong class="text-purple-400">1×24 jam</strong> setelah pembayaran diterima.'] as $i => $step)
                        <li class="flex items-start gap-3">
                            <span class="flex-shrink-0 w-5 h-5 bg-purple-600/30 border border-purple-500/40 text-purple-300 text-xs font-bold rounded-full flex items-center justify-center mt-0.5">{{ $i + 1 }}</span>
                            <p class="text-slate-400 text-sm leading-relaxed">{!! $step !!}</p>
                        </li>
                        @endforeach
                    </ol>
                </div>

                {{-- Dev Bypass Button --}}
                @if($transaction->status === 'pending')
                <div class="mt-6 text-center">
                    <form action="{{ route('admin.transaction.confirm', $transaction->invoice_number) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-fuchsia-600 to-pink-600 hover:from-fuchsia-500 hover:to-pink-500 text-white font-bold py-3 px-6 rounded-xl text-sm transition-all duration-200 border border-fuchsia-400 shadow-lg shadow-fuchsia-950/50 hover:scale-105 active:scale-95 cursor-pointer">
                            ⚡ Bypass Pembayaran (DEV ONLY)
                        </button>
                    </form>
                </div>
                @endif

            </div>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-slate-600 text-xs mt-6">
            SPECTA XXI: REVELIORA · SMAN 1 Cianjur · Simpan halaman ini sebagai referensi.
        </p>

    </div>
</div>

@push('scripts')
<script>
    function paymentTimer(invoice, initialStatus) {
        return {
            status: initialStatus,
            timeLeft: { hours: '02', minutes: '00', seconds: '00' },
            deadline: null,
            pollingInterval: null,
            countdownInterval: null,

            init() {
                // 2-hour window from page load
                this.deadline = new Date(Date.now() + 2 * 60 * 60 * 1000);
                if (this.status === 'pending') {
                    this.startCountdown();
                    this.startPolling();
                }
            },

            startCountdown() {
                this.countdownInterval = setInterval(() => {
                    const diff = this.deadline - Date.now();
                    if (diff <= 0) {
                        clearInterval(this.countdownInterval);
                        this.timeLeft = { hours: '00', minutes: '00', seconds: '00' };
                        return;
                    }
                    const h = Math.floor(diff / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);
                    this.timeLeft = {
                        hours:   String(h).padStart(2, '0'),
                        minutes: String(m).padStart(2, '0'),
                        seconds: String(s).padStart(2, '00'),
                    };
                }, 1000);
            },

            startPolling() {
                this.pollingInterval = setInterval(async () => {
                    try {
                        const res = await fetch(`/payment/${invoice}/status`);
                        const data = await res.json();
                        if (data.status !== 'pending') {
                            this.status = data.status;
                            clearInterval(this.pollingInterval);
                            clearInterval(this.countdownInterval);
                        }
                    } catch (e) { /* network hiccup, retry next tick */ }
                }, 10000); // poll every 10 seconds
            }
        };
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.textContent;
            btn.textContent = 'Disalin ✓';
            btn.classList.add('text-green-400', 'border-green-500/40');
            setTimeout(() => {
                btn.textContent = orig;
                btn.classList.remove('text-green-400', 'border-green-500/40');
            }, 2000);
        });
    }
</script>
@endpush
@endsection
