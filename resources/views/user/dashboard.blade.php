@extends('layouts.app')

@section('title', 'My Tickets – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 pt-24 pb-12 px-4 relative overflow-hidden">
    {{-- Decorative Background --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-purple-900/20 blur-[120px] rounded-full mix-blend-screen"></div>
        <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-cyan-900/20 blur-[120px] rounded-full mix-blend-screen"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        
        <div class="mb-8">
            <h1 class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 uppercase tracking-tight">
                My Tickets
            </h1>
            <p class="text-slate-400 mt-2">Daftar transaksi dan tiket SPECTA XXI: REVELIORA milik Anda.</p>
        </div>

        @if($transactions->isEmpty())
            <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-2xl p-8 text-center shadow-[0_0_40px_rgba(168,85,247,0.05)]">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-800 border border-slate-700 text-slate-400 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Belum ada tiket</h3>
                <p class="text-slate-400 mb-6">Anda belum melakukan pembelian tiket.</p>
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-[0_0_20px_rgba(168,85,247,0.3)] hover:shadow-[0_0_30px_rgba(168,85,247,0.5)]">
                    Beli Tiket Sekarang
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($transactions as $trx)
                    <div class="bg-white/5 border border-white/10 backdrop-blur-xl rounded-2xl p-6 shadow-[0_0_40px_rgba(168,85,247,0.05)] transition-transform hover:-translate-y-1">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 pb-6 border-b border-white/10">
                            <div>
                                <p class="text-sm text-slate-400 font-mono mb-1">Invoice: {{ $trx->invoice_number }}</p>
                                <h3 class="text-xl font-bold text-white">Tiket Reguler <span class="text-purple-400 ml-2">x{{ $trx->quantity }}</span></h3>
                                <p class="text-slate-300 font-semibold mt-1">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                @if($trx->status === 'SUCCESS')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-900/40 text-green-400 border border-green-600/40 shadow-[0_0_15px_rgba(34,197,94,0.2)]">
                                        BERHASIL
                                    </span>
                                @elseif($trx->status === 'PENDING_PROOF')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-cyan-900/40 text-cyan-400 border border-cyan-600/40 shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                        MENUNGGU PERSETUJUAN
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-900/40 text-red-400 border border-red-600/40 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                        DITOLAK / EXPIRED
                                    </span>
                                @endif
                                <p class="text-xs text-slate-500 mt-2">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($trx->status === 'SUCCESS')
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-900/50 rounded-xl p-4 mt-4">
                                <div>
                                    <p class="text-sm text-slate-300 font-medium">E-Ticket Anda sudah siap diunduh</p>
                                    <p class="text-xs text-slate-500 mt-1">Gunakan PDF ini pada saat masuk gate.</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <a href="{{ route('payment.show', $trx->invoice_number) }}" class="inline-flex justify-center items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                        Detail Transaksi
                                    </a>
                                    <a href="{{ route('ticket.download', $trx->download_token) }}" class="inline-flex justify-center items-center gap-2 bg-purple-600/20 hover:bg-purple-600/40 border border-purple-500/50 text-purple-300 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Unduh PDF
                                    </a>
                                </div>
                            </div>
                        @elseif($trx->status === 'PENDING_PROOF')
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-cyan-900/20 border border-cyan-900/50 rounded-xl p-4 mt-4">
                                <div class="text-sm text-cyan-200">
                                    <p class="font-medium">Menunggu Bukti / Verifikasi</p>
                                    <p class="text-xs text-cyan-400 mt-1">Silakan upload bukti atau cek status pembayaran Anda.</p>
                                </div>
                                <a href="{{ route('payment.show', $trx->invoice_number) }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-cyan-600/20 hover:bg-cyan-600/40 border border-cyan-500/50 text-cyan-300 px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                                    Buka Halaman Pembayaran
                                </a>
                            </div>
                        @else
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-red-900/20 border border-red-900/50 rounded-xl p-4 mt-4">
                                <div class="text-sm text-red-200">
                                    <p class="font-medium">Transaksi Ditolak</p>
                                    <p class="text-xs text-red-400 mt-1">Transaksi ini telah dibatalkan atau ditolak.</p>
                                </div>
                                <a href="{{ route('payment.show', $trx->invoice_number) }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 px-4 py-2 rounded-lg text-sm font-semibold transition-all whitespace-nowrap">
                                    Detail Transaksi
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        
        <div class="mt-8">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
