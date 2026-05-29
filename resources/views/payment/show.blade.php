@extends('layouts.app')

@section('title', 'Instruksi Pembayaran – ' . $transaction->invoice_number)

@section('content')
<div class="min-h-screen bg-slate-950 flex items-center justify-center px-4 py-12 relative overflow-hidden">

    {{-- Ambient glow blobs --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-700/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-2xl"
         x-data="paymentPage('{{ $transaction->invoice_number }}', '{{ $transaction->status }}')"
         x-init="init()">

        {{-- Header badge --}}
        @if($transaction->status === 'PENDING_PROOF')
        <div class="flex items-center justify-center mb-6">
            <div class="inline-flex items-center gap-2 bg-purple-900/40 border border-purple-500/40 text-purple-300 text-sm font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm">
                <svg class="w-4 h-4 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16A8 8 0 0010 2zm0 14a6 6 0 110-12 6 6 0 010 12zm.75-8.25a.75.75 0 00-1.5 0V10c0 .199.079.39.22.53l2 2a.75.75 0 001.06-1.06l-1.78-1.78V7.75z"/></svg>
                Menunggu Verifikasi Pembayaran
            </div>
        </div>
        @endif

        {{-- Main card --}}
        <div class="bg-slate-900/60 backdrop-blur-xl border border-slate-700/50 rounded-3xl shadow-2xl shadow-purple-900/20 overflow-hidden">

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

                {{-- Flash messages --}}
                @if(session('success'))
                <div class="bg-green-900/40 border border-green-500/40 rounded-xl p-4 mb-6 flex items-start sm:items-center gap-3">
                    <svg class="w-6 h-6 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-green-300 text-sm break-words">{{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-900/40 border border-red-500/40 rounded-xl p-4 mb-6 flex items-start gap-3">
                    <svg class="w-6 h-6 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div class="flex-1">
                        @foreach($errors->all() as $error)
                        <p class="text-red-300 text-sm break-words mb-1">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif

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
                    <div class="border-t border-purple-500/20 pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-slate-300 font-semibold uppercase tracking-wide text-sm">Total Transfer</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $transaction->quantity }} × Rp 100.000</p>
                            </div>
                            <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-cyan-300 font-mono">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>



                {{-- ── SUCCESS STATE ─────────────────────────────────────── --}}
                @if($transaction->status === 'SUCCESS')
                <div class="bg-green-900/40 border border-green-500/40 rounded-2xl p-8 text-center mb-6 space-y-6">
                    <div class="w-16 h-16 bg-green-500/10 border border-green-500/30 rounded-full flex items-center justify-center text-3xl mx-auto animate-bounce">
                        <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2m-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-green-400 font-black text-2xl tracking-wide">Pembayaran Dikonfirmasi!</h2>
                        <p class="text-slate-300 text-sm mt-2 leading-relaxed">
                            Terima kasih! Pembayaran Anda telah kami verifikasi. E-Tiket Anda sudah siap diunduh.
                        </p>
                    </div>

                    <a href="{{ route('ticket.download', $transaction->download_token) }}"
                       id="btn-download-ticket"
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-black py-4 px-8 rounded-2xl transition-all duration-300 shadow-lg shadow-purple-900/50 hover:shadow-purple-500/30 hover:-translate-y-0.5 active:translate-y-0 text-sm tracking-widest uppercase border border-purple-400/30 w-full justify-center">
                        <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M16 10h-2V7h-4v3H8l4 4m7 1h-4a3 3 0 0 1-3 3a3 3 0 0 1-3-3H5V5h14m0-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2"/></svg></span>
                        Download E-Tiket (PDF)
                    </a>

                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="{{ url('/') }}"
                           class="flex-1 py-3 bg-purple-600/20 hover:bg-purple-600/30 border border-purple-500/30 hover:border-purple-500/50 rounded-xl text-xs font-bold uppercase tracking-wider text-purple-300 text-center transition-all">
                            <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8z"/></svg> Halaman Utama
                        </a>
                        <a href="{{ route('tickets.index') }}"
                           class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 rounded-xl text-xs font-bold uppercase tracking-wider text-slate-300 text-center transition-all">
                            <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M15.58 16.8L12 14.5l-3.58 2.3l1.08-4.12L6.21 10l4.25-.26L12 5.8l1.54 3.94l4.25.26l-3.29 2.68M20 12a2 2 0 0 1 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2a2 2 0 0 1-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 1-2-2"/></svg> Beli Tiket Lagi
                        </a>
                    </div>
                </div>

                {{-- ── REJECTED STATE ────────────────────────────────────── --}}
                @elseif($transaction->status === 'REJECTED')
                <div class="bg-red-900/40 border border-red-500/40 rounded-2xl p-6 text-center mb-6 space-y-3">
                    <div class="text-5xl"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2c5.53 0 10 4.47 10 10s-4.47 10-10 10S2 17.53 2 12S6.47 2 12 2m3.59 5L12 10.59L8.41 7L7 8.41L10.59 12L7 15.59L8.41 17L12 13.41L15.59 17L17 15.59L13.41 12L17 8.41z"/></svg></div>
                    <p class="text-red-400 font-bold text-xl">Transaksi Ditolak</p>
                    <p class="text-slate-400 text-sm">Pembayaran Anda tidak dapat diverifikasi. Kuota tiket telah dikembalikan.</p>
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <a href="{{ url('/') }}"
                           class="flex-1 py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 rounded-xl text-xs font-bold uppercase tracking-wider text-slate-300 text-center transition-all">
                            <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8z"/></svg> Halaman Utama
                        </a>
                        <a href="{{ route('tickets.index') }}"
                           class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 rounded-xl text-xs font-bold uppercase tracking-wider text-white text-center transition-all">
                            <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M15.58 16.8L12 14.5l-3.58 2.3l1.08-4.12L6.21 10l4.25-.26L12 5.8l1.54 3.94l4.25.26l-3.29 2.68M20 12a2 2 0 0 1 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2a2 2 0 0 1-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 1-2-2"/></svg> Pesan Ulang
                        </a>
                    </div>
                </div>

                {{-- ── PENDING STATE ─────────────────────────────────────── --}}
                @else

                {{-- Bank account info & QRIS --}}
                <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-3">Transfer Ke Rekening</p>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between bg-slate-800/60 border border-slate-700/40 rounded-2xl px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-600/20 rounded-xl flex items-center justify-center">
                                        <span class="text-blue-400 font-black text-xs">BCA</span>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Bank Central Asia</p>
                                        <p class="font-mono font-bold text-slate-100 text-lg tracking-widest">3481002387</p>
                                        <p class="text-xs text-slate-400">a.n. Nabila Putri Nur Maulia</p>
                                    </div>
                                </div>
                                <button id="btn-copy-rekening"
                                        onclick="copyToClipboard('3481002387', this)"
                                        class="text-xs text-purple-400 hover:text-purple-300 font-semibold border border-purple-500/30 hover:border-purple-400/60 rounded-xl px-3 py-2 transition-all duration-200">
                                    Salin
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-widest mb-3">Atau Scan QRIS</p>
                        <div class="bg-white rounded-2xl p-4 flex justify-center items-center h-full border border-slate-700/40 overflow-hidden">
                            <img src="{{ asset('images/qris.png') }}" alt="QRIS Pembayaran" class="max-h-48 object-contain">
                        </div>
                    </div>
                </div>

                    {{-- Proof Upload Form --}}
                    <div class="bg-slate-800/40 border border-purple-500/20 rounded-2xl p-5 mb-6">
                        <p class="text-sm text-slate-300 font-semibold mb-1"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2m8 3a5 5 0 0 0-5 5a5 5 0 0 0 5 5a5 5 0 0 0 5-5a5 5 0 0 0-5-5m0 2a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3"/></svg> Upload Bukti Pembayaran</p>
                        <p class="text-xs text-slate-500 mb-4">
                            Setelah transfer/scan QRIS, upload screenshot atau foto bukti pembayaran Anda di sini.
                            @if($transaction->payment_proof)
                                <span class="text-green-400 ml-1"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M21 7L9 19l-5.5-5.5l1.41-1.41L9 16.17L19.59 5.59z"/></svg> Sudah diunggah — tim kami sedang memverifikasi.</span>
                            @endif
                        </p>

                        <form action="{{ route('payment.proof.upload', $transaction->invoice_number) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              id="proof-upload-form">
                            @csrf

                            <label for="payment_proof"
                                   class="flex flex-col items-center justify-center gap-3 w-full h-32 border-2 border-dashed border-slate-700 hover:border-purple-500/50 rounded-xl cursor-pointer bg-slate-900/40 transition-all group"
                                   x-data="{ fileName: '' }"
                                   @dragover.prevent
                                   @drop.prevent="fileName = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files">

                                <svg class="w-8 h-8 text-slate-600 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>

                                <span class="text-xs text-slate-500 group-hover:text-slate-400 transition-colors text-center"
                                      x-text="fileName || 'Klik atau seret file kesini\n(JPG, PNG, WEBP • max 10MB)'">
                                    Klik atau seret file ke sini (JPG, PNG, WEBP • maks 10MB)
                                </span>

                                <input id="payment_proof"
                                       name="payment_proof"
                                       type="file"
                                       accept="image/jpeg,image/png,image/webp"
                                       x-ref="fileInput"
                                       @change="fileName = $event.target.files[0]?.name"
                                       class="hidden">
                            </label>

                            <button type="submit"
                                    id="proof-submit-btn"
                                    class="mt-3 w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold py-3 rounded-xl text-sm tracking-wider uppercase transition-all shadow-lg shadow-purple-900/30 hover:shadow-purple-500/20 cursor-pointer">
                                Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>

                    {{-- Instructions --}}
                    <div class="bg-slate-800/40 border border-slate-700/30 rounded-2xl p-5">
                        <p class="text-xs text-slate-400 uppercase tracking-widest mb-3 font-semibold">Langkah Pembayaran</p>
                        <ol class="space-y-3">
                            @foreach([
                                'Transfer tepat <strong class="text-purple-400">Rp ' . number_format($transaction->total_price, 0, ',', '.') . '</strong> ke rekening BCA atau scan QRIS di atas.',
                                'Screenshot atau foto bukti transfer Anda.',
                                'Upload bukti di form di atas.',
                                'Tim kami akan memverifikasi dalam <strong class="text-purple-400">1×24 jam</strong>. E-Tiket otomatis muncul di halaman ini setelah dikonfirmasi.',
                            ] as $i => $step)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-5 h-5 bg-purple-600/30 border border-purple-500/40 text-purple-300 text-xs font-bold rounded-full flex items-center justify-center mt-0.5">{{ $i + 1 }}</span>
                                <p class="text-slate-400 text-sm leading-relaxed">{!! $step !!}</p>
                            </li>
                            @endforeach
                        </ol>
                    </div>

                @endif

            </div>
        </div>

        {{-- WhatsApp Contact Support --}}
        <div class="mt-8 text-center flex flex-col items-center">
            <p class="text-slate-500 text-xs mb-3">Butuh bantuan atau konfirmasi manual?</p>
            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Specta%20XXI,%20saya%20butuh%20bantuan%20terkait%20pesanan%20tiket%20saya%20dengan%20Invoice:%20{{ $transaction->invoice_number }}"
               target="_blank"
               class="inline-flex items-center gap-2 bg-[#25D366]/10 hover:bg-[#25D366]/20 border border-[#25D366]/50 text-[#25D366] px-5 py-2.5 rounded-full text-sm font-bold transition-all shadow-[0_0_15px_rgba(37,211,102,0.1)] hover:shadow-[0_0_25px_rgba(37,211,102,0.2)]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Hubungi Customer Service
            </a>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-slate-600 text-xs mt-6">
            SPECTA XXI: REVELIORA · SMAN 1 Cianjur · Simpan halaman ini sebagai referensi.
        </p>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function paymentPage(invoice, initialStatus) {
        return {
            status: initialStatus,

            init() {
                // Poll only if still pending
                if (this.status === 'PENDING_PROOF') {
                    this.startPolling();
                }
            },

            startPolling() {
                setInterval(async () => {
                    try {
                        const res  = await fetch(`/payment/${invoice}/status`);
                        const data = await res.json();
                        if (data.status !== 'PENDING_PROOF') {
                            window.location.reload();
                        }
                    } catch (e) { /* network hiccup */ }
                }, 15000); // Poll every 15 seconds
            }
        };
    }

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.textContent;
            btn.textContent = 'Disalin <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M21 7L9 19l-5.5-5.5l1.41-1.41L9 16.17L19.59 5.59z"/></svg>';
            btn.classList.add('text-green-400', 'border-green-500/40');
            setTimeout(() => {
                btn.textContent = orig;
                btn.classList.remove('text-green-400', 'border-green-500/40');
            }, 2000);
        });
    }
</script>
@endpush
